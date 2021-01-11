<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Services;

use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\Contracts\DuplicateProductRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts\ProductDuplicateGenerator as ProductDuplicateGeneratorContract;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts\Warehouse;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts\WarehouseRepository;
use Illuminate\Database\Eloquent\Model;

class ProductDuplicateGenerator implements ProductDuplicateGeneratorContract
{
    /** @var WarehouseRepository $warehouses */
    protected $warehouses;

    /** @var ProductRepository $products */
    protected $products;

    /** @var DuplicateProductRepository $duplicateProducts */
    protected $duplicateProducts;

    /** @var Model $product */
    private $product;

    /** @var array $checkProducts */
    private $checkProducts;

    /** @var array $settings */
    private $settings;

    /** @var array $activeSettings */
    private $activeSettings;

    /** @var array $tempDuplicateIds */
    private $tempDuplicateIds;

    /** @var array tempForWarehouseSavedProductIds */
    private $tempForWarehouseSavedProductIds;

    public function __construct(WarehouseRepository $warehouses, ProductRepository $products, DuplicateProductRepository $duplicateProducts)
    {
        $this->warehouses = $warehouses;
        $this->products = $products;
        $this->duplicateProducts = $duplicateProducts;
    }

    /**
     * Generate warehouse
     *
     * @param Warehouse $warehouse
     */
    public function generateWarehouse(Warehouse $warehouse)
    {
        $this->resetTempForWarehouseSavedProductIds();
        if ($this->getActiveSettings()){
            $products = $warehouse->products()->cursor();
            foreach ($products as $product){
                $productId = $product->id;
                if (!in_array($productId, $this->tempForWarehouseSavedProductIds)){
                    $this->generate($product);
                }
            }
        }
    }

    /**
     * Generate
     *
     * @param Model $product
     */
    public function generate(Model $product): void
    {
        $this->setProduct($product);
        $this->resetTempDuplicateIds();
        if ($this->getActiveSettings()){
            $this->resetCheckProducts();
            $this->addCheckProduct($product);
            $this->searchDuplicates();
            $this->saveDuplicates();
        }
    }

    /**
     * Set product
     *
     * @param Model $product
     * @return $this
     */
    private function setProduct(Model $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Check products
     *
     * @param $product
     */
    private function addCheckProduct($product)
    {
        $productId = $product->id;
        $checkProduct = [];
        foreach ($this->getActiveSettings() as $key => $active) {
            $checkProduct['id'] = $productId;
            if ($key === 'gtin') {
                $checkProduct[$key] = $product->$key;
            }
            else if ($key === 'external_id') {
                $checkProduct[$key] = $product->$key;
            }
        }
        if ($checkProduct) {
            $this->checkProducts[$productId] = $checkProduct;
        }
    }

    /**
     * Get settings
     *
     * @return array|null
     */
    private function getSettings()
    {
        if (!$this->settings) {
            $this->settings = $this->warehouses->find($this->product->warehouse_id)->duplicate_product_settings;
        }
        return $this->settings;
    }

    /**
     * Reset temp duplicate IDs
     */
    private function resetTempDuplicateIds()
    {
        $this->tempDuplicateIds = [];
    }

    /**
     * Search duplicates
     */
    private function searchDuplicates()
    {
        $checkedProductIds = [];
        for ($i = 0; $i < 100; $i++) {
            if ($this->emptyCheckProducts()) {
                break;
            }
            $checkProduct = array_shift($this->checkProducts);
            array_push($checkedProductIds, $checkProduct['id']);
            $searchProducts = collect();
            foreach ($this->getActiveSettings() as $key => $active) {
                if ($key === 'gtin' && $checkProduct['gtin']) {
                    $searchProducts = $searchProducts->merge($this->products->findWhere([
                        'warehouse_id' => $this->product->warehouse_id,
                        'gtin' => $checkProduct['gtin'],
                    ]));
                } else if ($key === 'external_id' && $checkProduct['external_id']) {
                    $searchProducts = $searchProducts->merge($this->products->findWhere([
                        'warehouse_id' => $this->product->warehouse_id,
                        'external_id' => $checkProduct['external_id'],
                    ]));
                }
            }
            foreach ($searchProducts as $searchProduct){
                if (!in_array($searchProduct->id, $checkedProductIds)){
                    if (!in_array($searchProduct->id, $this->tempDuplicateIds)){
                        array_push($this->tempDuplicateIds, $searchProduct->id);
                    }
                    $this->addCheckProduct($searchProduct);
                }
            }
        }
    }

    /**
     * Empty check products
     *
     * @return bool
     */
    private function emptyCheckProducts()
    {
        return !sizeof($this->checkProducts);
    }

    /**
     * Get active settings
     *
     * @return array|null
     */
    private function getActiveSettings()
    {
        if (!$this->activeSettings) {
            foreach ($this->getSettings() as $key => $active) {
                if ($active) {
                    $this->activeSettings[$key] = $active;
                }
            }
        }
        return $this->activeSettings;
    }

    /**
     * Save duplicates
     */
    private function saveDuplicates()
    {
        $productIds = array_merge($this->tempDuplicateIds, [$this->product->id]);
        foreach ($productIds as $productId){
            $tempDuplicateProductIds = array_diff($productIds, [$productId]);
            $databaseDuplicateProductIds = $this->duplicateProducts->findWhere(['warehouse_id'=>$this->product->warehouse_id, 'product_id' =>$productId], ['duplicate_product_id'])->pluck('duplicate_product_id')->toArray();
            $allDuplicateProductIds = array_merge($tempDuplicateProductIds, $databaseDuplicateProductIds);
            foreach ($allDuplicateProductIds as $duplicateProductId){
                if (!in_array($duplicateProductId, $databaseDuplicateProductIds)){
                    $this->duplicateProducts->create([
                        'user_id'=>$this->product->user_id,
                        'warehouse_id'=>$this->product->warehouse_id,
                        'product_id' =>$productId,
                        'duplicate_product_id' =>$duplicateProductId,
                    ]);
                } else if (!in_array($duplicateProductId, $tempDuplicateProductIds)){
                   $this->duplicateProducts->deleteWhere([
                       'user_id'=>$this->product->user_id,
                       'warehouse_id'=>$this->product->warehouse_id,
                       'product_id' =>$productId,
                       'duplicate_product_id' =>$duplicateProductId,
                   ]);
                }
            }
        }
        if ($this->tempForWarehouseSavedProductIds){
            $this->tempForWarehouseSavedProductIds = array_merge($this->tempForWarehouseSavedProductIds, $productIds);
        }
    }

    /**
     * Reset check products
     */
    private function resetCheckProducts()
    {
        $this->checkProducts = [];
    }

    /**
     * Reset saved product IDs for a warehouse.
     */
    private function resetTempForWarehouseSavedProductIds()
    {
        $this->tempForWarehouseSavedProductIds = [];
    }
}
