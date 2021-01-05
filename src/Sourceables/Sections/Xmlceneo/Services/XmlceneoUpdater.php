<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoUpdater as XmlceneoUpdaterContract;

class XmlceneoUpdater implements XmlceneoUpdaterContract
{
    /**
     * Update products
     *
     * @param Xmlceneo $xmlceneo
     * @param array $options
     */
    public function updateProducts(Xmlceneo $xmlceneo, $options=[]): void{

    }
}
