<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Resources;

use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentDuplicateProduct extends JsonResource
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
            'duplicate_product' => EloquentProduct::make($this->duplicateProduct),
            'product' => EloquentProduct::make($this->product),
            'external_id' => $this->external_id,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
