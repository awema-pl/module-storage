<?php

namespace AwemaPL\Storage\User\Sections\Categories\Resources;

use AwemaPL\Storage\User\Sections\Warehouses\Resources\EloquentWarehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentCategory extends JsonResource
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
            'crumbs' => $this->crumbs(),
            'parent'=>EloquentCategory::make($this->parent),
            'parent_crumbs' => $this->parentCrumbs(),
            'external_id' => $this->external_id,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
