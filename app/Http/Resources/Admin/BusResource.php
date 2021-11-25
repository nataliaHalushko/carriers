<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed schema
 * @property mixed number
 * @property mixed carrier_id
 * @property mixed carrier
 * @property mixed schema_id
 * @property mixed comfort
 */
class BusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'brand'     =>  $this->model->brand->name,
            'model'     =>  $this->model->name,
            'number'    =>  $this->number,
            'carrier'   =>  [
                'id'=>$this->carrier_id,
            ],
            'schema'    =>  [
                'id' => $this->schema_id,
                'template'=>$this->schema->template
            ],
            'comfort'   =>  $this->comfort,
        ];
    }
}
