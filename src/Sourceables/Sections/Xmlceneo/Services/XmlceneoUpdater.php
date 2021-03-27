<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services;

use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoUpdater as XmlceneoUpdaterContract;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Repositories\Contracts\DescriptionRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts\ProductDuplicateGenerator;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\Contracts\ManufacturerRepository;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;
use AwemaPL\Xml\Client\Readers\Requests\Contracts\Ceneo as CeneoContract;
use AwemaPL\Xml\Client\Readers\Responses\Response;
use AwemaPL\Xml\Client\XmlClient;
use AwemaPL\Xml\User\Sections\Ceneosources\Services\Contracts\DataExtractor;
use Illuminate\Database\Eloquent\Model;
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
    
    /** @var ProductDuplicateGenerator $productDuplicateGenerator */
    protected $productDuplicateGenerator;

    /** @var DataExtractor $dataExtractor */
    private $dataExtractor;

    /** @var array $options */
    private $options;

    public function __construct(ProductRepository $products, ProductDuplicateGenerator $productDuplicateGenerator)
    {
        $this->products = $products;
        $this->productDuplicateGenerator = $productDuplicateGenerator;
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
        /** @var Response $productResponse */
        foreach ($products as $productResponse) {
            $xml = $productResponse->xml();
            $externalId = $this->getDataExtractor()->getId($xml);
            $product = $this->updateProduct($externalId, $xml);
            if ($product && $this->getOption('generate_duplicate_product')){
                $this->productDuplicateGenerator->generate($product);
            }
        }
    }

    /**
     * Update product
     *
     * @param string $externalId
     * @param SimpleXMLElement $xml
     * @return Product|Model|null
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
            if ($this->canUpdate('description')){
                $this->updateDescription($product, $xml);
            }
            if ($this->canUpdate('features')){
                $this->updateFeatures($product, $xml);
            }
        }
        return $product;
    }

    /**
     * Update description
     * 
     * @param Model $product
     * @param SimpleXMLElement $xmlProduct
     */
    private function updateDescription(Model $product, SimpleXMLElement $xmlProduct){
        $contentDescription = $this->dataExtractor->getDescription($xmlProduct);
        $product->descriptions()->update([
            'value' =>$contentDescription, 
        ]);
    }

    /**
     * Update features
     *
     * @param Model $product
     * @param SimpleXMLElement $xmlProduct
     */
    private function updateFeatures(Model $product, SimpleXMLElement $xmlProduct){
        $attributes = $this->dataExtractor->getAttributes($xmlProduct);
        $data = [];
        $product->features()->forceDelete();
        foreach ($attributes as $attribute){
            array_push($data, [
                'name' => $attribute['name'],
                'value' => $attribute['value'],
                'user_id' =>$this->source->user_id,
                'warehouse_id' =>$this->source->warehouse_id,
                'product_id' =>$product->getKey(),
                'created_at' =>now(),
            ]);
        }
        if ($data){
            $product->features()->insert($data);
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
        $optionValue = $this->options[$element] ?? false;
        return (bool) $optionValue;
    }

    /**
     * Get option
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    private function getOption($key, $default = null){
        return $this->options[$key] ?? $default;
    }
}
