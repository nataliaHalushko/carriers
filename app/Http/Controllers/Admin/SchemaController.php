<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\PaginateCollection;
use App\Models\Bus;
use App\Models\Carrier;
use App\Models\Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\BinaryOp\Identical;
use Symfony\Component\HttpFoundation\JsonResponse;
use function GuzzleHttp\Psr7\build_query;

class SchemaController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $schema = Schema::select('id','name')->get();
        return $this->sendResponse(new PaginateCollection($schema),'Success');
    }
    /**
     * @param Bus $bus
     * @return JsonResponse
     */
    public function show(Bus $bus): JsonResponse
    {
        $response = [
            'brand'     =>  $bus->model->brand->name,
            'model'     =>  $bus->model->name,
            'number'    =>  $bus->number,
            'carrier_id'=>  $bus->carrier_id,
            'schema_id' =>  $bus->schema_id,
            'comfort'   =>  $bus->comfort,
            'schema'    =>  $bus->schema->template
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



        return $this->sendResponse([
            'brand'     =>  $bus->model->brand->name,
            'model'     =>  $bus->model->name,
            'number'    =>  $bus->number,
            'carrier_id'=>  $bus->carrier_id,
            'schema_id' =>  $bus->schema_id,
            'comfort'   =>  $bus->comfort,
            'schema'    =>  $bus->schema->template
        ],'Success');
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

        return $this->sendResponse([
            'brand'     =>  $bus->model->brand->name,
            'model'     =>  $bus->model->name,
            'number'    =>  $bus->number,
            'carrier_id'=>  $bus->carrier_id,
            'schema_id' =>  $bus->schema_id,
            'comfort'   =>  $bus->comfort,
            'schema'    =>  $bus->schema->template
        ],'Success');
    }

    public function destroy(Bus $bus){
        $bus->delete();

        return $this->sendResponse([],'Success');
    }

    public function form(){
        $response = [
            'schema'    =>  Schema::all()->pluck('name','id'),
            'carrier'   =>  Carrier::all()->pluck('name','id')
        ];
        return $this->sendResponse($response,'Success');
    }
}
