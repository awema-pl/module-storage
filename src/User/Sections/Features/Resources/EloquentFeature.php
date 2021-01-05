<?php

namespace AwemaPL\Storage\User\Sections\Features\Resources;

use AwemaPL\Storage\User\Sections\Categories\Resources\EloquentCategory;
use AwemaPL\Storage\User\Sections\Features\Services\FeatureType;
use AwemaPL\Storage\User\Sections\Manufacturers\Resources\EloquentManufacturer;
use AwemaPL\Storage\User\Sections\Features\Services\Contracts\Availability;
use AwemaPL\Storage\User\Sections\Products\Resources\EloquentProduct;
use AwemaPL\Storage\User\Sections\Variants\Resources\EloquentVariant;
use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentFeature extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var FeatureType $featureType */
        $featureType = app(FeatureType::class);

        return [
            'id' => $this->id,
            'warehouse' => EloquentWarehouse::make($this->warehouse),
            'product' => EloquentProduct::make($this->product),
            'variant' => EloquentVariant::make($this->variant),
            'name' => $this->name,
            'value' => $this->value,
            'type' => $this->type,
            'type_name' => optional($this->type, function() use (&$featureType){
                return $featureType->getTypeName($this->type);
            }),
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
