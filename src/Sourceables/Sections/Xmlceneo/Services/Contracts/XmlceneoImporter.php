<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;

interface XmlceneoImporter
{
    /**
     * Import products
     *
     * @param Xmlceneo $xmlceneo
     * @param array $options
     */
    public function importProducts(Xmlceneo $xmlceneo, $options=[]): void;
}
