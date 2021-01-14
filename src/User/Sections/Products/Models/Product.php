<?php

namespace AwemaPL\Storage\User\Sections\Products\Models;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Models\CategoryProduct;
use AwemaPL\Storage\User\Sections\Descriptions\Models\Description;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\DuplicateProduct;
use AwemaPL\Storage\User\Sections\Features\Models\Feature;
use AwemaPL\Storage\User\Sections\Images\Models\Image;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Products\Models\Contracts\Product as ProductContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
       'user_id', 'warehouse_id', 'default_category_id', 'manufacturer_id', 'source_id','active', 'name', 'gtin','sku','stock','availability',
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
        'default_category_id' => 'integer',
        'manufacturer_id' => 'integer',
        'source_id' => 'integer',
        'active' => 'boolean',
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
     * Get the default category that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function defaultCategory(){
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
     * Get the source that owns the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source(){
        return $this->belongsTo(Source::class);
    }

    /**
     * The categories that belong to the product.
     *
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, config('storage.database.tables.storage_category_product'))->withTimestamps();;
    }

    /**
     * Get all of the descriptions for the product.
     *
     * @return HasMany
     */
    public function descriptions(){
        return $this->hasMany(Description::class);
    }

    /**
     * Get all of the variants for the product.
     *
     * @return HasMany
     */
    public function variants(){
        return $this->hasMany(Variant::class);
    }

    /**
     * Get all of the images for the product.
     *
     * @return HasMany
     */
    public function images(){
        return $this->hasMany(Image::class);
    }

    /**
     * Get all of the features for the product.
     *
     * @return HasMany
     */
    public function features(){
        return $this->hasMany(Feature::class);
    }

    /**
     * The categories that belong to the product.
     *
     * @return BelongsToMany
     */
    public function duplicates()
    {
        return $this->belongsToMany(Product::class, config('storage.database.tables.storage_duplicate_products'), 'duplicate_product_id')->withTimestamps();;
    }

    /**
     * Get category IDs
     *
     * @return array
     */
    public function getCategoryIds(){
        return CategoryProduct::where('product_id', $this->id)->get('category_id')->pluck('category_id')->toArray();
    }

    /**
     * Get feature by name
     *
     * @param string $name
     * @return string|null
     */
    public function getFeatureByName(string $name): ?string{
        $feature = $this->features()->where('name', $name)->first();
        if ($feature){
            return (string) $feature->value;
        }
        return null;
    }
}
