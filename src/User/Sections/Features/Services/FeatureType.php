<?php
namespace AwemaPL\Storage\User\Sections\Features\Services;

use AwemaPL\Storage\User\Sections\Features\Services\Contracts\FeatureType as FeatureTypeContract;
use ReflectionClass;

class FeatureType implements FeatureTypeContract
{

    const WEIGHT = 'weight';

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes():array  {
        $reflectionClass = new ReflectionClass(FeatureType::class);
        $data = [];
        foreach ($reflectionClass->getConstants() as $key => $type){
            $key = mb_strtolower($key);
            array_push($data, [
                'name' =>  _p('storage::pages.user.feature.types.' . $key, str_replace('_', ' ', $key)),
                'type' => $type,
            ]);
        }
        return $data;
    }

    /**
     * Get type name
     *
     * @param $value
     * @return string
     */
    public function getTypeName($value):string{
        foreach ($this->getTypes() as $type){
            if ($value === $type['type']){
               return $type['name'];
            }
        }
        return $value;
    }
}
