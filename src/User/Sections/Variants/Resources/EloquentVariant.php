<?php

namespace AwemaPL\Storage\User\Sections\Variants\Resources;

use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use AwemaPL\Storage\User\Sections\Manufacturers\Resources\EloquentManufacturer;
use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentVariant extends JsonResource
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
            'product' => EloquentProduct::make($this->product),
            'active' => $this->active,
            'name' => $this->name,
            'ean' => $this->ean,
            'sku' => $this->sku,
            'stock' => $this->stock,
            'availability' => $this->availability,
            'availability_name' => $availability->getAvailabilityName($this->availability),
            'brutto_price' => $this->brutto_price,
            'external_id' => $this->external_id,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
