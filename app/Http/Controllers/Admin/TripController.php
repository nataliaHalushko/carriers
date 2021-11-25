<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateCollection;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Trip;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Symfony\Component\HttpFoundation\JsonResponse;

class TripController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $trips = Trip::with(['bus.carrier','tripRoute'])->get();

        $trips = $trips->map(function ($value){
            return[
                "id"=> $value->id,
                "tripNumber"=> $value->number,
                "route"=> "Вінниця - Ладижин ",
                "carrier"=> $value->bus->carrier->name,
                "timeFrom"=> "18:10",
                "timeTo"=> "18:55",
                "seats"=> 21,
                "change"=> true,
                "delete"=> true
            ];
        });

        return $this->sendResponse(new PaginateCollection($trips),'Success');
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $route = Route::whereNull('parent_id')->findOrFail($id);


        return $this->sendResponse([$route],'Success');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return $this->sendResponse([],'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        return $this->sendResponse([],'Success');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Trip $trip)
    {
        $trip->delete();
        return $this->sendResponse([],'Success');
    }
}
