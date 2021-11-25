<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateCollection;
use App\Models\Bus;
use App\Models\Carrier;
use App\Models\Driver;
use App\Models\Route;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Symfony\Component\HttpFoundation\JsonResponse;

class DriverController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $drivers = Driver::with(['carrier'])->get();

        $drivers = $drivers->map(function ($value){
            return[
                "id"=> $value->id,
                "driver"=> $value->last_name. ' '. $value->first_name[0].'. '.$value->surname[0] ,
                "licence"=> $value->licence,
                "carrier"=> $value->carrier->name,
                "delete"=> true
            ];
        });

        return $this->sendResponse(new PaginateCollection($drivers),'Success');
    }
    /**
     * @param Driver $driver
     * @return JsonResponse
     */
    public function show(Driver $driver): JsonResponse
    {
        $response = [
            "id"=> $driver->id,
            "last_name" => $driver->last_name,
            "first_name" => $driver->first_name,
            "surname"=> $driver->surname,
            "licence"=> $driver->licence,
            "date_licence"=> $driver->date_licence,
            "categories"=> $driver->category,
            "carrier"=> [$driver->carrier_id => $driver->carrier->name],
            "date_medical"=> $driver->date_medical,
            "phone"=> $driver->phone,
        ];
        return $this->sendResponse($response,'Success');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'last_name' => 'required',
            'first_name'=>'required',
            'licence'=>'required',
            'date_licence'=>'required',
            'category'=>'required|array',
            'carrier_id'=>'required',
            'date_medical'=>'required',
            'phone'=>'required',

        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }
        $input['date_licence'] = Carbon::create($input['date_licence']);
        $input['date_medical'] = Carbon::create($input['date_medical']);
        $driver = new Driver($input);
        $driver->save();

        return $this->sendResponse($driver,'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request,Driver $driver)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'last_name' => 'required',
            'first_name'=>'required',
            'licence'=>'required',
            'date_licence'=>'required',
            'category'=>'required|array',
            'carrier_id'=>'required',
            'date_medical'=>'required',
            'phone'=>'required',

        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }

        $driver->update($input);

        return $this->sendResponse($driver,'Success');
    }

    public function destroy(Driver $driver){
        try {
            $driver->delete();
            return $this->sendResponse([],'Success');
        }
        catch (\Exception $exception){
            return $this->sendError('d',$exception->getMessage());
        }


    }

    public function form(){

        return $this->sendResponse([
            "categories"  =>  Driver::CATEGORIES,
            "carrier"   =>  Carrier::all()->pluck('name','id')
        ],'Success');
    }
}
