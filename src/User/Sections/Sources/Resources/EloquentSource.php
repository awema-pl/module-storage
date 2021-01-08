<?php

namespace AwemaPL\Storage\User\Sections\Sources\Resources;

use AwemaPL\Storage\User\Sections\Sources\Services\SourceTypes;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentSource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var SourceTypes $sourceTypes */
        $sourceTypes = app(SourceTypes::class);

        return [
            'id' => $this->id,
            'warehouse' => $this->warehouse,
            'sourceable_id' => optional($this->sourceable)->getKey(),
            'sourceable_type' => optional($this->sourceable, function(){
                return get_class($this->sourceable);
            }),
            'sourceable_type_name' => optional($this->sourceable, function () use (&$sourceTypes){
                return $sourceTypes->getName(get_class($this->sourceable));
            }),
            'source_name' => optional($this->sourceable)->getName(),
            'settings' => $this->settings,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
