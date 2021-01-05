<?php

namespace AwemaPL\Storage\User\Sections\Features\Models;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\CategoriesFeatures\Models\CategoryFeature;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Features\Models\Contracts\Feature as FeatureContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model implements FeatureContract
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
       'user_id', 'warehouse_id', 'product_id', 'variant_id', 'name', 'value', 'type',
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
        'variant_id' => 'integer',
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
        return config('storage.database.tables.storage_features');
    }

    /**
     * Get the user that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the warehouse that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant that owns the feature.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant(){
        return $this->belongsTo(Variant::class);
    }
}
