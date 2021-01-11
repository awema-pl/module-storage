<?php

namespace AwemaPL\Storage\User\Sections\Products\Resources;

use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use AwemaPL\Storage\User\Sections\Manufacturers\Resources\EloquentManufacturer;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentProduct extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var Availability $availability */
        $availability = app(Availability::class);

        return [
            'id' => $this->id,
            'warehouse' => EloquentWarehouse::make($this->warehouse),
            'default_category' => EloquentCategory::make($this->defaultCategory),
            'manufacturer' => EloquentManufacturer::make($this->manufacturer),
            'active' => $this->active,
            'name' => $this->name,
            'gtin' => $this->gtin,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'availability' => $this->availability,
            'availability_name' => $availability->getAvailabilityName($this->availability),
            'brutto_price' => $this->brutto_price,
            'tax_rate' => $this->tax_rate,
            'external_id' => $this->external_id,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
