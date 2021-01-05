<?php

namespace AwemaPL\Storage\User\Sections\Sources\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\User\Sections\Sources\Services\Contracts\SourceType as SourceTypeContract;
use InvalidArgumentException;

class SourceTypes implements SourceTypeContract
{
    const TYPES = [
        Xmlceneo::class =>[
            'default_name' =>'XML Ceneo',
            'key' => 'xmlceneo',
        ]
    ];

    /**
     * Get types
     *
     * @return array
     */
    public function getTypes()
    {
        return self::TYPES;
    }

    /**
     * Get type
     *
     * @param $type
     * @return string
     */
    public function getType($type)
    {
        if (!isset($this->getTypes()[$type])){
            throw new InvalidArgumentException("Type not exist $type");
        }
        return $type;
    }

    /**
     * Get name
     *
     * @param $type
     * @return string
     */
    public function getName($type){
        $type = $this->getType($type);
        $key = $this->getTypes()[$type]['key'];
        $defaultName = $this->getTypes()[$type]['default_name'];
        return _p('storage::pages.user.source.types.' . $key, $defaultName);
    }
}
