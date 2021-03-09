<?php

namespace AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services;

use AwemaPL\BaseJS\Exceptions\PublicException;
use AwemaPL\Storage\Common\Exceptions\StorageException;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Models\Xmlceneo;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoImporter as XmlceneoImporterContract;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Services\DescriptionType;
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
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Throwable;

class XmlceneoImporter implements XmlceneoImporterContract
{
    /** @var SourceContract $source */
    private $source;

    /** @var Xmlceneo $xmlceneo */
    private $xmlceneo;

    /** @var CeneoContract $xmlceneoClient */
    private $xmlceneoClient;

    /** @var ProductRepository $products */
    private $products;

    /** @var ManufacturerRepository $manufacturers */
    private $manufacturers;

    /** @var CategoryRepository $categories */
    private $categories;

    /** @var ProductDuplicateGenerator $productDuplicateGenerator */
    protected $productDuplicateGenerator;

    /** @var DataExtractor $dataExtractor */
    private $dataExtractor;

    /** @var array $sourceSettings */
    private $sourceSettings;

    /** @var array $tempManufacturerIds */
    private $tempManufacturerIds;

    /** @var array $tempCategoryIds */
    private $tempCategoryIds;

    public function __construct(ProductRepository $products, ManufacturerRepository $manufacturers, CategoryRepository $categories, ProductDuplicateGenerator $productDuplicateGenerator)
    {
        $this->products = $products;
        $this->manufacturers = $manufacturers;
        $this->categories = $categories;
        $this->productDuplicateGenerator = $productDuplicateGenerator;
        $this->tempManufacturerIds = [];
        $this->tempCategoryIds = [];
    }

    /**
     * Import products
     *
     * @param SourceContract $source
     * @param array $options
     */
    public function importProducts(SourceContract $source, array $options = []): void
    {
        $this->setSource($source);
        $products = $this->getXmlceneoClient()->all();
        /** @var Response $productResponse */
        foreach ($products as $productResponse) {
            $xml = $productResponse->xml();
            $externalId = $this->getDataExtractor()->getId($xml);
            dump('exist by external ID product ' . $externalId);
            if (!$this->products->existsByExternalId($source->warehouse->id, $externalId, $source->getKey())) {
                dump('import product ' . $externalId);
                $product = $this->importProduct($externalId, $xml);
                $this->productDuplicateGenerator->generate($product);
            }
        }
    }

    /**
     * Import product
     *
     * @param string $externalId
     * @param SimpleXMLElement $xml
     * @return Product
     */
    public function importProduct(string $externalId, SimpleXMLElement $xml)
    {
        $attributes = $this->getDataExtractor()->getAttributes($xml);
        $manufacturerId = $this->importManufacturerId($attributes);
        $categoryIds = $this->importCategories($this->getDataExtractor()->getCat($xml));
        $defaultCategoryId = array_pop($categoryIds);
        $product = $this->products->create([
            'name' => $this->getProductName($xml),
            'gtin' => $this->dataExtractor->getAttrubuteValue('GTIN', $attributes, true),
            'sku' => $this->dataExtractor->getAttrubuteValue('SKU', $attributes, true),
            'stock' => $this->dataExtractor->getStock($xml),
            'availability' => $this->dataExtractor->getAvailability($xml),
            'brutto_price' => $this->dataExtractor->getBruttoPrice($xml),
            'tax_rate' => $this->getTaxRate($xml),
            'external_id' => $externalId,
            'user_id' => $this->source->user_id,
            'warehouse_id' => $this->source->warehouse->id,
            'default_category_id' => $defaultCategoryId,
            'manufacturer_id' => $manufacturerId,
            'source_id' => $this->source->getKey(),
        ]);
        try{
            $this->importCategoriesProducts($categoryIds, $product);
            $this->importDescription($this->getDataExtractor()->getDescription($xml), $product);
            $this->importImages($this->getDataExtractor()->getImages($xml), $product);
            $this->importFeatures($attributes, $product);
        } catch (Throwable $exception){
            $product->delete();
            throw $exception;
        }
        $product->active = true;
        $product->save();
        return $product;
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
     * Get source settings
     *
     * @return array
     */
    private function getSourceSettings()
    {
        if (!$this->sourceSettings) {
            $this->sourceSettings = $this->source->settings;
        }
        return $this->sourceSettings;
    }

    /**
     * Get source setting
     *
     * @param $key
     * @param null $default
     * @return array|\ArrayAccess|mixed
     */
    private function getSourceSetting($key, $default = null)
    {
        return Arr::get($this->getSourceSettings(), $key, $default);
    }

    /**
     * Import manufacturer ID
     *
     * @param array $attributes
     * @return int|null
     */
    private function importManufacturerId(array $attributes)
    {
        $manufacturerAttributeName = $this->getSourceSetting('manufacturer_attribute_name');
        $id = null;
        if ($manufacturerAttributeName) {
            $manufacturerName = $this->dataExtractor->getAttrubuteValue($manufacturerAttributeName, $attributes, true);
            if ($manufacturerName){
                $id = $this->getManufacturerId($manufacturerName);
            }
        }
        return $id;
    }

    /**
     * Get manufacturer ID
     *
     * @param $manufacturerName
     * @return mixed
     */
    private function getManufacturerId($manufacturerName)
    {
        if (isset($this->tempManufacturerIds[$manufacturerName])) {
            return $this->tempManufacturerIds[$manufacturerName];
        }
        $manufacturer = $this->manufacturers->firstOrCreate([
            'source_id' => $this->source->getKey(),
            'name' => $manufacturerName,
        ], [
            'user_id' => $this->source->user_id,
            'warehouse_id' => $this->source->warehouse_id,
        ]);
        $id = $manufacturer->getKey();
        $this->tempManufacturerIds[$manufacturerName] = $id;
        return $id;
    }

    /**
     * Import categories
     *
     * @param $crumbs
     * @return array|mixed
     * @throws StorageException
     */
    public function importCategories($crumbs)
    {
        if (!$crumbs) {
            $crumbs = _p('storage::business.sourceable.xmlceneo.no_category', 'No category');
       }
        if (isset($this->tempCategoryIds[$crumbs])) {
            return $this->tempCategoryIds[$crumbs];
        }
        $categoryIds = [];
        $parentId = null;
        $externalId = '';
        foreach (explode('/', $crumbs) as $crumb) {
            $externalId .= $externalId ? '/' : '';
            $externalId .= $crumb;
            $externalIdLimit = $externalId;
            if (mb_strlen($externalIdLimit) > 255){
                $externalIdLimit = $this->mb_strrev($externalIdLimit);
                $externalIdLimit = Str::limit($externalIdLimit, 252, '');
                $externalIdLimit = '...' .  $this->mb_strrev($externalIdLimit);
                dump('4 $externalIdLimit: ' . $externalIdLimit);
            }
            $category = $this->categories->firstOrCreate([
                'source_id' => $this->source->getKey(),
                'external_id' => $externalIdLimit,
                'parent_id' => $parentId,
            ], [
                'name' => $crumb,
                'warehouse_id' => $this->source->warehouse_id,
                'user_id' => $this->source->user_id,
            ]);
            $categoryId = $category->getKey();
            array_push($categoryIds, $categoryId);
            $parentId = $categoryId;
        }
        $this->tempCategoryIds[$crumbs] = $categoryIds;
        return $this->tempCategoryIds[$crumbs];
    }

    /**
     * String reverse
     *
     * @param $string
     * @param null $encoding
     * @return string
     */
    private function mb_strrev ($string, $encoding = null) {
        if ($encoding === null) {
            $encoding = mb_detect_encoding($string);
        }
        $length   = mb_strlen($string, $encoding);
        $reversed = '';
        while ($length-- > 0) {
            $reversed .= mb_substr($string, $length, 1, $encoding);
        }
        return $reversed;
    }

    /**
     * Get tax rate
     *
     * @param SimpleXMLElement $xml
     * @return array|\ArrayAccess|int|mixed
     */
    private function getTaxRate(SimpleXMLElement $xml)
    {
        $taxRateArray = $xml->xpath('//@tax');
        if (sizeof($taxRateArray)) {
            return (int)$taxRateArray[0];
        }
        return $this->getSourceSetting('default_tax_rate', null);
    }

    /**
     * Import categories products
     *
     * @param array $categoryIds
     * @param Product $product
     */
    private function importCategoriesProducts(array $categoryIds, Product $product): void
    {
        $associativeCategoryIds = [];
        foreach ($categoryIds as $categoryId){
            $associativeCategoryIds[$categoryId] =[
                'user_id' =>$this->source->user_id,
                'warehouse_id' =>$this->source->warehouse_id,
            ];
        }
        $product->categories()->attach($associativeCategoryIds);
    }

    /**
     * Impoer description
     *
     * @param string $value
     * @param Product $product
     */
    private function importDescription(string $value, Product $product): void
    {
        $product->descriptions()->create([
            'type' =>DescriptionType::DEFAULT,
            'value' => $value,
            'user_id'=>$this->source->user_id,
            'warehouse_id' =>$this->source->warehouse_id,
        ]);
    }

    /**
     * Import images
     *
     * @param array $images
     * @param Product $product
     */
    private function importImages(array $images, Product $product)
    {
        $data = [];
        foreach ($images as $image){
            array_push($data, [
                'url' => $image['url'],
                'external_id' => $this->dataExtractor->buildImageId($image['url']),
                'main' =>$image['main'],
                'user_id' =>$this->source->user_id,
                'warehouse_id' =>$this->source->warehouse_id,
                'product_id' =>$product->getKey(),
                'source_id' =>$this->source->getKey(),
                'created_at' =>now(),
            ]);
        }
        if ($data){
            $product->images()->insert($data);
        }
    }

    /**
     * Import features
     *
     * @param array $attributes
     * @param Product $product
     */
    public function importFeatures(array $attributes,Product $product)
    {
        $data = [];
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
     * Get product name
     *
     * @param SimpleXMLElement $xml
     * @return string
     */
    public function getProductName(SimpleXMLElement $xml):string
    {
        $name = $this->dataExtractor->getName($xml);
        if (mb_strlen($name) > 255){
            $name = Str::limit($name, 255, '');
        }
        return $name;
    }
}
