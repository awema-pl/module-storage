<?php

namespace AwemaPL\Storage\User\Sections\Manufacturers\Resources;

use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentManufacturer extends JsonResource
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
            'id' => $this->id,
            'warehouse' => EloquentWarehouse::make($this->warehouse),
            'name' => $this->name,
            'image_url'=>$this->image_url,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
