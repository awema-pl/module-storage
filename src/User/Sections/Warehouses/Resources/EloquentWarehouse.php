<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentWarehouse extends JsonResource
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
            'name' => $this->name,
            'duplicate_product_settings' => $this->duplicate_products,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
