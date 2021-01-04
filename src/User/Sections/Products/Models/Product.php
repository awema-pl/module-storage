<?php

namespace AwemaPL\Storage\User\Sections\Products\Models;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Models\CategoryProduct;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Products\Models\Contracts\Product as ProductContract;

class Product extends Model implements ProductContract
{
    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'warehouse_id', 'category_id', 'manufacturer_id', 'name', 'ean','sku','stock','availability',
       'brutto_price','tax_rate', 'external_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'warehouse_id' => 'integer',
        'category_id' => 'integer',
        'manufacturer_id' => 'integer',
        'stock' => 'integer',
        'brutto_price' => 'float',
        'tax_rate' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('storage.database.tables.storage_products');
    }

    /**
     * Get the user that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the warehouse that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(){
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the manufacturer that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

    /**
     * Assign categories products
     */
    public function assignCategoriesProducts(){
        $newCategoryIds = $this->category->getParentCategories()->pluck('id')->toArray();
        array_push($newCategoryIds, $this->category_id);
        throw new \Exception(json_encode($newCategoryIds));
        $categoriesProducts = CategoryProduct::where('product_id', $this->id)->get();
        foreach ($categoriesProducts as $categoryProduct){
            if (!in_array($categoryProduct->id, $newCategoryIds)){
                $categoryProduct->delete();
            } else {
                $newCategoryIds = array_diff($newCategoryIds, [$categoryProduct->id]);
            }
        }
        $insertBatch = [];
        foreach ($newCategoryIds as $newCategoryId){
            array_push($insertBatch,[
                'user_id' =>$this->user_id,
                'warehouse_id' =>$this->warehouse_id,
                'category_id'=>$newCategoryId,
                'product_id' =>$this->id,
            ]);
            CategoryProduct::insert($insertBatch);
        }
    }
}
