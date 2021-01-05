<?php

namespace AwemaPL\Storage\User\Sections\Variants\Models;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\CategoriesVariants\Models\CategoryVariant;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Variants\Models\Contracts\Variant as VariantContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Variant extends Model implements VariantContract
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
       'user_id', 'warehouse_id', 'product_id', 'name', 'ean','sku','stock','availability',
       'brutto_price', 'external_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'warehouse_id' => 'integer',
        'product_id' => 'integer',
        'stock' => 'integer',
        'brutto_price' => 'float',
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
        return config('storage.database.tables.storage_variants');
    }

    /**
     * Get the user that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the warehouse that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product that owns the variant.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
