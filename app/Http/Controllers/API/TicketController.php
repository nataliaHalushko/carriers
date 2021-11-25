<?php

namespace App\Http\Controllers\API;

use App\Helpers\LiqPayAPI;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\ScheduleResource;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\Ticket;
use App\Models\Trip;
use App\Models\TripRoute;
use App\Models\User;
use Carbon\Carbon;
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
    private $from_id;
    private $to_id;
    private $date;
    private $trip_id;

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

        $this->from_id = $request->get('from_id');
        $this->to_id = $request->get('to_id');
        $this->date = $request->get('date') ? Carbon::create($request->get('date')) : Carbon::now();
        $this->trip_id = $request->get('trip_id');

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

            ->whereId($this->trip_id)
            ->first();

        $tripRoute = $schedule->trip->tripRoute->first();

        $departure = $schedule->trip->tripRoute->where('stop_id',$request->get('from_id'))->first();
        $arrival = $departure->info->where('to_id',$request->get('to_id'))->first();

        $bookedSeat = array_unique( $schedule->tickets->pluck('seat')->toArray());


        $result = [
            'id' => $schedule->id,
            'trip'=>[
                'trip_name'=>$schedule->trip->getRouteName(),
                'route'=>[
                    'from'=>[
                        'id'=>$tripRoute->stop_id,
                        'time'=>$tripRoute->departure,
                        'date'=>$this->date->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$tripRoute->stop->lat.",".$tripRoute->stop->lng."&query_place_id=".$tripRoute->stop->place_id,
                        'settlement'=>$tripRoute->stop->settlement->name,
                        'address'=>$tripRoute->stop->name,
                    ],
                    'to'=>[
                        'id'=>$arrival->to_id,
                        'time'=>$arrival->parent->arrival,
                        'date'=>$this->date->format('d-M'),
                        'link_maps'=>"https://www.google.com/maps/search/?api=1&query=".$arrival->stop->lat.",".$arrival->stop->lng."&query_place_id=".$departure->stop->place_id,
                        'settlement'=>$arrival->stop->settlement->name,
                        'address'=>$arrival->stop->name,
                    ],

                ],
                'interval'=> $arrival->distance,
                'price'=>$arrival->price


            ],
            'free_seat'=> $schedule->trip->bus->countSeat() - count($bookedSeat),
            'schema'=>$schedule->trip->bus->getSchema($bookedSeat),

        ];


        return $this->sendResponse($result);

    }

    public function ticketBuy(Request $request){

        $input = $request->all();

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
                $input['user']['password'] = bcrypt(12345);
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
           // ->whereDate('date',$this->date)
            ->whereId($this->trip_id)
            ->first();


        $tripRoute = $schedule->trip->tripRoute->first();

        $departure = $schedule->trip->tripRoute->where('stop_id',$request->get('from_id'))->first();
        $arrival = $departure->info->where('to_id',$request->get('to_id'))->first();

        $order = new Order();
        $order->uid = Order::whereDate('created_at',Carbon::now()->format('Y-m-d'))->count().'/'.Carbon::now()->format('dmy').'/'.Order::whereYear('created_at',Carbon::now()->format('Y'))->count();
        $order->sum = $tripRoute->price*count($input['seats']);
        $order->user_id = $user->id ?? 1;

        $order->save();

        foreach ($input['seats'] as $seat){

            $ticket = new Ticket();
            $ticket->user_id = $user->id ?? null;
            $ticket->schedules_id = $input['trip_id'];
            $ticket->device_id = $input['device_id'] ?? null;
            $ticket->fname = $input['user']['first_name'];
            $ticket->lname = $input['user']['last_name'];
            $ticket->phone = $input['user']['phone'];
            $ticket->email = $input['user']['email'];
            $ticket->order_id = $order->id;
            $ticket->from_id = $this->from_id;
            $ticket->to_id = $this->to_id;
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
