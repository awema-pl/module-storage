<?php

namespace AwemaPL\Storage\User\Sections\CategoriesProducts\Resources;

use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentCategoryProduct extends JsonResource
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
            'category' => EloquentCategory::make($this->category),
            'product' => EloquentProduct::make($this->product),
            'created_at' =>optional($this->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
