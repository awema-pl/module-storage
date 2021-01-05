<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoImporter as XmlceneoImporterContract;

class XmlceneoImporter implements XmlceneoImporterContract
{
    /**
     * Import products
     *
     * @param Xmlceneo $xmlceneo
     * @param array $options
     */
    public function importProducts(Xmlceneo $xmlceneo, $options=[]): void{

    }
}
