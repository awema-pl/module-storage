<?php

namespace AwemaPL\Storage\User\Sections\Warehouses\Models;

use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Task\User\Sections\Statuses\Models\Contracts\Taskable;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts\Warehouse as WarehouseContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model implements WarehouseContract
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
       'user_id', 'name', 'duplicate_product_settings'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'duplicate_product_settings' =>'array',
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
        return config('storage.database.tables.storage_warehouses');
    }

    /**
     * Get the user that owns the warehouse.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get all of the products for the product.
     *
     * @return HasMany
     */
    public function products(){
        return $this->hasMany(Product::class);
    }

}
