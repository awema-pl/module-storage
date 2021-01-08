<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Models;

use AwemaPL\Storage\Common\Exceptions\StorageException;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\Contracts\DuplicateProduct as DuplicateProductContract;
use Illuminate\Support\Collection;

class DuplicateProduct extends Model implements DuplicateProductContract
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
       'user_id', 'warehouse_id', 'product_id', 'duplicate_product_id',
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
        'duplicate_product_id' =>'integer',
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
        return config('storage.database.tables.storage_duplicate_products');
    }


    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function(Model $model){
            if ($model->duplicate_product_id === $model->product_id){
                throw new StorageException('Duplicate product and product cannot be the same.', StorageException::ERROR_SAME_VALUES, 409, null,
                    _p('storage::exceptions.user.duplicate_product.duplicate_product_and_product_not_same', 'Duplicate product and product cannot be the same.'), null, false);
            }
        });
        self::updating(function(Model $model){
            if ($model->duplicate_product_id === $model->product_id){
                throw new StorageException('Duplicate product and product cannot be the same.', StorageException::ERROR_SAME_VALUES, 409, null,
                    _p('storage::exceptions.user.duplicate_product.duplicate_product_and_product_not_same', 'Duplicate product and product cannot be the same.'), null, false);
            }
        });
    }

    /**
     * Get the user that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the warehouse that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(){
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the duplicate product that owns the duplicate product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function duplicateProduct(){
        return $this->belongsTo(Product::class);
    }
}
