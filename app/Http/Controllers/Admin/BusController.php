<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Admin\BusResource;
use App\Http\Resources\PaginateCollection;
use App\Models\Brand;
use App\Models\Bus;
use App\Models\Carrier;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $buses = Bus::all();

        $buses = $buses->map(function ($value){
            return[
                "id"=> $value->id,
                "number"=> $value->number,
                "mark"=> $value->model->name,
                "seats"=> $value->count_seat,
                "carrier"=> $value->carrier->name,
                "delete"=> true
            ];
        });

        return $this->sendResponse(new PaginateCollection($buses),'Success');
    }
    /**
     * @param Bus $bus
     * @return JsonResponse
     */
    public function show(Bus $bus): JsonResponse
    {
        return $this->sendResponse(new BusResource($bus),'Success');
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
            'number'=>'required',
            'carrier_id'=>'required',
            'schema_id'=>'required',
            'comfort'=>'required|array',
            'numbering'=>'required|array'
        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }
        $bus = new Bus($input);
        $bus->save();

        return $this->sendResponse(new BusResource($bus),'Success');
    }

    /**
     * @param Request $request
     * @param Bus $bus
     * @return JsonResponse
     */
    public function update(Request $request, Bus $bus): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'number'=>'required',
            'carrier_id'=>'required',
            'schema_id'=>'required',
            'comfort'=>'required|array',
            'numbering'=>'required|array'
        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }

        $bus->update($input);

        return $this->sendResponse(new BusResource($bus),'Success');
    }

    public function destroy(Bus $bus){
        $bus->delete();

        return $this->sendResponse([],'Success');
    }

    public function form(){
        $response = [
            'schema'    =>  Schema::all()->pluck('name','id'),
            'carrier'   =>  Carrier::all()->pluck('name','id'),
            'brand'   =>  Brand::all()->pluck('name','id'),

        ];
        dd($response);
        return $this->sendResponse($response,'Success');
    }





}
