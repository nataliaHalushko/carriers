<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SettlementController extends BaseController
{

    public function settlementSearch(Request $request){

        if ($request->get("name")){
            $predictions = Http::get('https://maps.googleapis.com/maps/api/place/autocomplete/json', [
                'key' => env('GOOGLE_API_KEY'),
                'input'=>$request->get("name"),
                'language'=>'uk',
                'types'=>'(cities)'
            ]);
            $predictions= json_decode($predictions->body())->predictions;

            $response = [];

            foreach ($predictions as $key =>$prediction){
                $response[$key]['city']=$prediction->description;
                $response[$key]['place_id']=$prediction->place_id;
            }


        }
        else
            return $this->sendError('gg','gg',403);


        return $this->sendResponse($response);

    }

    public function settlementPopular(){

        $settlements = Settlement::with('region.country')->get();
        $settlements = $settlements->map(
           function ($value){
               return [
                   'city'=>$value->name.', '.$value->region->name.' область, '.$value->region->country->name,
                   'place_id'=>$value->place_id,
               ];
           }
        );

        return $this->sendResponse($settlements);

    }

}
