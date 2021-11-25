<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CarrierResource;
use App\Http\Resources\PaginateCollection;
use App\Models\Bus;
use App\Models\Carrier;
use App\Models\Route;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Symfony\Component\HttpFoundation\JsonResponse;

class CarrierController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $carrier = Carrier::all();

        $carrier = $carrier->map(function ($value){
            return[
                "id"=> $value->id,
                "carrier"=> $value->name,
                "routeQuantity"=> $value->buses->sum(function ($q){
                    return $q->trips->count();
                }),
                "tripQuantity"=> $value->buses->sum(function ($q){
                    return $q->trips->count();
                }),
                "busQuantity"=> $value->buses->count(),
                "driverQuantity"=> $value->drivers->count(),
                "contact"=> $value->phone,
                "delete"=> true
            ];
        });

        return $this->sendResponse(new PaginateCollection($carrier),'Success');
    }

    /**
     * @param Carrier $carrier
     * @return JsonResponse
     */
    public function show(Carrier $carrier): JsonResponse
    {
        return $this->sendResponse(new CarrierResource($carrier),'Success');
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
            'name' => 'required',
            'address'=>'required',
            'phone'=>'required',
            'contact_person'=>'required',
            'liqpay'=>'required',

        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }
        $carrier = new Carrier($input);
        $carrier->save();


        return $this->sendResponse(new CarrierResource($carrier),'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request,Carrier $carrier)
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'address'=>'required',
            'phone'=>'required',
            'contact_person'=>'required',
            'liqpay'=>'required',

        ]);

        if($validator->fails()){
            return $this->sendError('Помилка валідації', $validator->errors());
        }
        $carrier->update($input);

        return $this->sendResponse(new CarrierResource($carrier),'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Carrier $carrier)
    {
        $carrier->delete();
        return $this->sendResponse([],'Success');
    }
}
