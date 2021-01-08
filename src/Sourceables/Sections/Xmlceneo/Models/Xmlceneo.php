<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoImporter;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoUpdater;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Sourceable;
use AwemaPL\Xml\User\Sections\Ceneosources\Models\Ceneosource;

class Xmlceneo extends Ceneosource implements Sourceable
{
    /** @var XmlceneoImporter $importer */
    protected $importer;

    /** @var XmlceneoUpdater $updater */
    protected $updater;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
       return $this->name;
    }

    /**
     * Import products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function importProducts(SourceContract $source, $options=[]): void{
        $this->getImporter()->importProducts($source, $options);
    }

    /**
     * Update products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function updateProducts(SourceContract $source, $options=[]): void{
        $this->getUpdater()->updateProducts($source, $options);
    }

    /**
     * Get importer
     *
     * @return XmlceneoImporter
     */
    public function getImporter(){
        if (!$this->importer){
            $this->importer = app(XmlceneoImporter::class);
        }
        return $this->importer;
    }

    /**
     * Get updater
     *
     * @return XmlceneoUpdater
     */
    public function getUpdater(){
        if (!$this->updater){
            $this->updater = app(XmlceneoUpdater::class);
        }
        return $this->updater;
    }
}
