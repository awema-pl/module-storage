<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoUpdater as XmlceneoUpdaterContract;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\Contracts\ManufacturerRepository;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;
use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;
use AwemaPL\Xml\Client\Readers\Responses\Response;
use AwemaPL\Xml\Client\XmlClient;
use AwemaPL\Xml\User\Sections\Ceneosources\Services\Contracts\DataExtractor;
use SimpleXMLElement;

class XmlceneoUpdater implements XmlceneoUpdaterContract
{
    /** @var SourceContract $source */
    private $source;

    /** @var Xmlceneo $xmlceneo */
    private $xmlceneo;

    /** @var CeneoContract $xmlceneoClient */
    private $xmlceneoClient;

    /** @var ProductRepository $products */
    private $products;

    /** @var DataExtractor $dataExtractor */
    private $dataExtractor;

    /** @var array $options */
    private $options;

    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }
    
    /**
     * Update products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function updateProducts(SourceContract $source, $options=[]): void{
        $this->setSource($source);
        $this->setOptions($options);
        $products = $this->getXmlceneoClient()->all();
        /** @var Response $product */
        foreach ($products as $product) {
            $xml = $product->xml();
            $externalId = $this->getDataExtractor()->getId($xml);
            $this->updateProduct($externalId, $xml);
        }
    }

    /**
     * Update product
     *
     * @param string $externalId
     * @param SimpleXMLElement $xml
     */
    public function updateProduct(string $externalId, SimpleXMLElement $xml)
    {
        $product = $this->products->firstWhere([
            'source_id'=>$this->source->getKey(),
            'external_id' =>$externalId,
        ]);
        if ($product){
            $data = [];
            $this->canUpdate('stock') ? $data['stock'] = $this->getDataExtractor()->getStock($xml) : null;
            $this->canUpdate('availability') ? $data['availability'] = $this->getDataExtractor()->getAvailability($xml) : null;
            $this->canUpdate('brutto_price') ? $data['brutto_price'] = $this->getDataExtractor()->getBruttoPrice($xml) : null;
            if ($data){
                dump('update product ' . $externalId);
                $product->update($data);
            }
        }
    }

    /**
     * Set source
     *
     * @param SourceContract $source
     * @return $this
     */
    private function setSource(SourceContract $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Set options
     *
     * @param array $options
     * @return $this
     */
    private function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Get data extractor
     *
     * @return DataExtractor
     */
    private function getDataExtractor()
    {
        if (!$this->dataExtractor) {
            $this->dataExtractor = app(DataExtractor::class);
        }
        return $this->dataExtractor;
    }

    /**
     * Get XML Ceneo client
     *
     * @return CeneoContract
     */
    private function getXmlceneoClient()
    {
        if (!$this->xmlceneoClient) {
            $this->xmlceneoClient = (new XmlClient([
                'url' => $this->getXmlceneo()->url,
                'download_before' => true
            ]))->ceneo();
        }
        return $this->xmlceneoClient;
    }

    /**
     * Get XML Ceneo
     *
     * @return Xmlceneo
     */
    private function getXmlceneo()
    {
        if (!$this->xmlceneo) {
            $this->xmlceneo = $this->source->sourceable;
        }
        return $this->xmlceneo;
    }

    /**
     * Can update
     *
     * @param string $element
     * @return bool
     */
    private function canUpdate(string $element): bool
    {
        return (bool) $this->options[$element] ?? false;
    }
}
