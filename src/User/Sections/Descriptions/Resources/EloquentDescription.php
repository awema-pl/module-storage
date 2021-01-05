<?php

namespace AwemaPL\Storage\User\Sections\Descriptions\Resources;

use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use AwemaPL\Storage\User\Sections\Descriptions\Services\DescriptionType;
use AwemaPL\Storage\User\Sections\Manufacturers\Resources\EloquentManufacturer;
use AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts\Availability;
use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class EloquentDescription extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var DescriptionType $descriptionType */
        $descriptionType = app(DescriptionType::class);

        return [
            'id' => $this->id,
            'warehouse' => EloquentWarehouse::make($this->warehouse),
            'product' => EloquentProduct::make($this->product),
            'value' => $this->value,
            'value_truncate' => Str::limit($this->value),
            'type' => $this->type,
            'type_name' => $descriptionType->getTypeName($this->type),
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
