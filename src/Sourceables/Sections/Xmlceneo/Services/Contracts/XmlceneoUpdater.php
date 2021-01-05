<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;

interface XmlceneoUpdater
{
    /**
     * Update products
     *
     * @param Xmlceneo $xmlceneo
     * @param array $options
     */
    public function updateProducts(Xmlceneo $xmlceneo, $options=[]): void;
}
