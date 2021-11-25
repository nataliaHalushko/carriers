<?php

namespace App\Http\Controllers\API\Mobile;


use App\Helpers\LiqPayAPI;
use App\Http\Controllers\BaseController;
use App\Http\Resources\Mobile\ScheduleResource;
use App\Models\Order;
use App\Models\Redirect;
use App\Models\Schedule;
use App\Models\Ticket;
use App\Models\Trip;
use App\Models\TripRoute;
use App\Models\User;
use Carbon\Carbon;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use function Psy\debug;

class TicketController extends BaseController
{

    /**
     * @var mixed
     */
    protected $from_id;
    protected $to_id;
    protected $count;
    protected $trip_id;



    public function ticketCheckout(Request $request){

        $input = $request->all();

        $validator = Validator::make($input, [
            'trip_id'=>'required',
            'from_id'=>'required',
            'to_id'=>'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }

        $this->from_id= $request->get('from_id');
        $this->to_id= $request->get('to_id');
        $this->count= $request->get('count');
        $this->trip_id = $request->get('trip_id');

        $schedules = Schedule::with(
            [
                'trip.tripRoute.stop.settlement',
                'trip.tripRoute.parent.stop.settlement',
                'trip.tripRoute.info.stop.settlement',
                'carrier',
                'bus',
                'driver',
                'tickets'
            ])
            ->whereTripId($this->trip_id)->first();

        $current = $schedules->trip->tripRoute->where('stop_id', '=', $this->from_id)->first();
        $to = $schedules->trip->tripRoute->where('stop_id', '=', $this->to_id)->first();

        $bookedSeat = array_unique( $schedules->tickets->pluck('seat')->toArray());

        $trip_min = strtotime($to->departure) > strtotime($current->arrival) ? (strtotime($to->departure) - strtotime($current->arrival )) : 24 - (strtotime($current->arrival) - strtotime($to->departure));

        $int = new DateInterval("PT{$trip_min}S");


        $now = new DateTimeImmutable('now', new DateTimeZone('utc'));

        $trip_time = $now->diff($now->add($int))->format(' %h год. %i хв.');

        $result = [
            'id' => $schedules->id,
            'trip'=>[
                'trip_name'=>$schedules->trip->getRouteName(),
                'trip_time' => $trip_time,
                'route'=>[
                    'from'=>[
                        'id'=>$current->stop_id,
                        'time'=>$current->arrival,
                        'date'=>Carbon::create($schedules->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$current->stop->lat.",".$current->stop->lng."&query_place_id=".$current->stop->place_id,
                        'settlement'=>$current->stop->settlement->name,
                        'address'=>$current->stop->name,
                        'lat'=>$current->stop->lat,
                        'lng'=>$current->stop->lng,
                    ],
                    'to'=>[
                        'id'=>$to->stop_id,
                        'time'=>$to->departure,
                        'date'=>Carbon::create($schedules->date)->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$current->stop->lat.",".$current->stop->lng."&query_place_id=".$current->stop->place_id,
                        'settlement'=>$to->stop->settlement->name,
                        'address'=>$to->stop->name,
                        'lat'=>$to->stop->lat,
                        'lng'=>$to->stop->lng,
                    ]

                ],
                'interval'=> $current->info->where('to_id',$to->stop_id)->first()->distance,
                'price'=>$current->info->where('to_id',$to->stop_id)->first()->prive


            ],
            'free_seat'=> $schedules->trip->bus->countSeat() -  count($bookedSeat),
            'schema'=>$schedules->trip->bus->getSchema($bookedSeat),

        ];


        return $this->sendResponse($result);

    }

    public function ticketBuy(Request $request)
    {


        $input = $request->all();


        //перевірка скільки квитків купив незареєстрований юзер

        $tickets = Ticket::whereDeviceId($request->get('device_id'))
            ->count();

        if ($tickets > 0 ){

            $user_id = Ticket::whereDeviceId($request->get('device_id'))->first()->user_id;

            if (User::find($user_id)->password === null) {

                return $this->sendResponse('Зареєструйтесь, щоб купити квиток');
            }
        }


        $validator = Validator::make($input, [
            'from_id'=>'required',
            'to_id'=>'required',
            'trip_id'=>'required',
            'seats'=>'required|array',
        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors(),422);
        }
        $this->from_id = $request->get('from_id');
        $this->to_id = $request->get('to_id');
        $this->trip_id = $request->get('trip_id');

        if (auth('api')->check()){
            $user = Auth::guard('api')->user();
        }
        else{
            $user = User::wherePhone($input['user']['phone']??'test')
                ->orWhere('email','like',$input['user']['email']??'test')
                ->first();
            if (empty($user)){
                //$input['user']['password'] = bcrypt(12345);
                $user = User::create($input['user']);
            }

        }

        $schedule = Schedule::with(
            [
                'trip.tripRoute.stop.settlement',
                'trip.tripRoute.parent.stop.settlement',
                'trip.tripRoute.info.stop.settlement',
                'trip.tripRoute.info.parent.stop.settlement',
                'carrier',
                'bus',
                'driver',
                'tickets'
            ])
//            ->whereHas('trip.tripRoute.stop.settlement',function ($query){
//                $query->wherePlaceId($this->from_id);
//            })
//            ->whereHas('trip.tripRoute.parent.stop.settlement',function ($query){
//                $query->wherePlaceId($this->to_id);
//            })
            ->whereId($this->trip_id)
            ->first();

        $tripRoute = $schedule->trip->tripRoute->first();

        $departure = $schedule->trip->tripRoute->where('stop_id',$request->get('from_id'))->first();
        $arrival = $departure->info->where('to_id',$request->get('to_id'))->first();

        $order = new Order();
        $order->uid = Order::whereDate('created_at',Carbon::now()->format('Y-m-d'))->count().'/'.Carbon::now()->format('dmy').'/'.Order::whereYear('created_at',Carbon::now()->format('Y'))->count();
        $order->sum = $tripRoute->price*count($input['seats']);
        $order->user_id = $user->id ?? null;

        $order->save();

        foreach ($input['seats'] as $seat){

            $ticket = new Ticket();
            $ticket->user_id = $user->id ?? null;
            $ticket->device_id = $input['device_id'];
            $ticket->schedules_id = $input['trip_id'];
            $ticket->order_id = $order->id;
            $ticket->from_id = $this->from_id;
            $ticket->to_id = $this->to_id;
            $ticket->fname = $input['user']['first_name'];
            $ticket->lname = $input['user']['last_name'];
            $ticket->phone = $input['user']['phone'];
            $ticket->email = $input['user']['email'];
            $ticket->price = $arrival->price;
            $ticket->seat = $seat;

            $ticket->save();
            $ticket->qr = asset("qr-codes/" . $ticket->id . ".png");
            $ticket->save();
        }

        QrCode::size(500)->format('png')->generate($ticket->id, public_path('qr-codes')."/". $ticket->id .".png");


        $liqPay = new LiqPayAPI(env("LIQPAY_PUBLIC_KEY"),env('LIQPAY_PRIVAT_KEY'));

        $params =array(
            'action' => 'pay',
            'amount' =>  $order->sum,
            'currency' => 'UAH',
            'description' => 'Оплата квитка на BurBurBus',
            'order_id' => $order->uid,
            'version' => '3',
            'sandbox' => '1',
            'email' => $user->email,
            'sender_first_name' => $user->first_name,
            'sender_last_name' => $user->last_name,
            'result_url' => env('LIQPAY_CALLBACK_URL'),
            'split_rules'=>[
                [
                    'public_key'=>'sandbox_i27589357018',
                    'amount' =>  $order->price/2,
                    'commission_payer'=>'receiver',
                    'server_url'=>env('LIQPAY_CALLBACK_URL'),
                ],
                [
                    'public_key'=>'sandbox_i69914382905',
                    'amount' =>  $order->price/2,
                    'commission_payer'=>'receiver',
                    'server_url'=>env('LIQPAY_CALLBACK_URL'),
                ],
            ]
        );




        $link = $liqPay->getLink($params);


        return $this->sendResponse(['redirect_link'=>$link]);
    }

    public function ticketMy(Request $request){

        if (Auth::id() !== null){
            $tickets = Ticket::where('user_id',Auth::id())->get();
        }elseif($request->get('device_id') !== null){
            $tickets = Ticket::where('device_id',$request->get('device_id'))->get();
        }else{
            $tickets = [];
        }


        $result = [];
        foreach ($tickets as $ticket){
            $schedule = Schedule::with(
                [
                    'trip.tripRoute.stop.settlement',
                    'trip.tripRoute.parent.stop.settlement',
                    'trip.tripRoute.info.stop.settlement',
                    'trip.tripRoute.info.parent.stop.settlement',
                    'carrier',
                    'bus',
                    'driver',
                    'tickets'
                ])
                ->whereId($ticket->schedules_id)
                ->first();

            $from = $schedule->trip->tripRoute->where('stop_id',$ticket->from_id)->first();
            $to = $from->info->where('to_id',$ticket->to_id)->first();
            \request()->request->add(['from_id'=>$from->stop->settlement->place_id,'to_id'=>$to->stop->settlement->place_id]);


            if (Carbon::create($ticket->date)->timestamp > Carbon::now()->timestamp){
                $result['archive'][] = [
                    'id'=>$ticket->id,
                    'from'=>$from->stop->settlement->name,
                    'to'=>$to->stop->settlement->name,
                    'time'=>$from->departure,
                    'seat'=>$ticket->seat,
                    'trip'=>'Рейс '.$schedule->trip->number,
                    'qr'=>$ticket->qr,
                    'notification'=>Carbon::create($schedule->date)->addHour(-1)->format('H:i - d.m.Y'),
                    'notification_time'=>Carbon::create($schedule->date)->addHour(-1)->format('H:i'),
                    'notification_date'=>Carbon::create($schedule->date)->format('d.m.Y'),
                    'google_maps'=>'https://www.google.com.ua/maps/?q=' . $to->stop->settlement->lat . ',' . $to->stop->settlement->lng . '&ll=' . $to->stop->settlement->lat . ',' . $to->stop->settlement->lng . '&z=13' ,
                    'schedule'=>ScheduleResource::make($schedule)
                ];
            }else{
                $result['active'][] = [
                    'id'=>$ticket->id,
                    'from'=>$from->stop->settlement->name,
                    'to'=>$to->stop->settlement->name,
                    'date'=>Carbon::create($schedule->date)->format('d.m.Y'),
                    'time'=>$from->departure,
                    'seat'=>$ticket->seat,
                    'trip'=>'Рейс '.$schedule->trip->number,
                    'qr'=>$ticket->qr,
                    'google_maps'=>'https://www.google.com.ua/maps/?q=' . $to->stop->settlement->lat . ',' . $to->stop->settlement->lng . '&ll=' . $to->stop->settlement->lat . ',' . $to->stop->settlement->lng . '&z=13' ,
                    'schedule' => ScheduleResource::make($schedule)->toArray(\request())
                ];
            }




        }

        return $this->sendResponse($result);
    }

    public function liqpayCallback(Request $request){

        Log::debug($request->all());

        $liqPay = new LiqPayAPI(env("LIQPAY_PUBLIC_KEY"),env('LIQPAY_PRIVAT_KEY'));
        $response = $liqPay->decode_params($request->get('data'));

        $order = Order::find($response['order_id']);
        $order->payment_at = Carbon::now();
        $order->update();

        return redirect('/tickets');

    }
}
