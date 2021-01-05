<?php
namespace AwemaPL\Storage\User\Sections\Descriptions\Services;

use AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts\DescriptionType as DescriptionTypeContract;
use ReflectionClass;

class DescriptionType implements DescriptionTypeContract
{

    const DEFAULT = 'default';
    const ADDITIONAL_1 = 'additional_1';
    const ADDITIONAL_2 = 'additional_2';
    const ADDITIONAL_3 = 'additional_3';
    const ADDITIONAL_4 = 'additional_4';

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes():array  {
        $reflectionClass = new ReflectionClass(DescriptionType::class);
        $data = [];
        foreach ($reflectionClass->getConstants() as $key => $type){
            $key = mb_strtolower($key);
            array_push($data, [
                'name' =>  _p('storage::pages.user.description.types.' . $key, str_replace('_', ' ', $key)),
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

    /**
     * Get default
     *
     * @return string
     */
    public function getDefault(): string{
        return self::DEFAULT;
    }
}
