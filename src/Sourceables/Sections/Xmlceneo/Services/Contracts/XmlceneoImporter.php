<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;

interface XmlceneoImporter
{
    /**
     * Import products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function importProducts(SourceContract $source, array $options=[]): void;
}
