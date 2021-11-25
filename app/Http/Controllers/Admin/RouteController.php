<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaginateCollection;
use App\Models\Route;
use App\Models\TripRoute;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Symfony\Component\HttpFoundation\JsonResponse;

class RouteController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $routes = TripRoute::with(['child.stop.settlement','stop.settlement'])->get();
        $from = $routes->sortByDesc('distance')->first();
        $to = $routes->sortBy('distance')->first();

        $routes = $routes->map(function ($value) use ($from,$to){
            return[
                'id'=>$value->id,
                'route'=>$from->stop->settlement->name.' - '.$to->stop->settlement->name,
                'from'=>$from->stop->settlement->name,
                'to'=>$to->stop->settlement->name,
                'time'=>"1 год 20 хв",
                'distance'=>$from->distance
            ];
        });

        return $this->sendResponse(new PaginateCollection($routes),'Success');
    }
    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $route = Route::findOrFail($id);


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
}
