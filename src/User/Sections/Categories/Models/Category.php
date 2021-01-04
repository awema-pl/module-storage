<?php

namespace AwemaPL\Storage\User\Sections\Categories\Models;

use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Categories\Models\Contracts\Category as CategoryContract;

class Category extends Model implements CategoryContract
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
       'user_id', 'warehouse_id', 'name', 'parent_id', 'external_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'warehouse_id' => 'integer',
        'parent_id' => 'integer',
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
        return config('storage.database.tables.storage_categories');
    }

    /**
     * Get the user that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the warehouse that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the parent category that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(){
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get crumbs
     *
     * @return string
     */
    public function crumbs(){
        $name = $this->parentCrumbs();
        $name .= !empty($name) ? '/' : '';
        $name .= $this->name;
        return $name;
    }

    /**
     * Get parent crumbs
     *
     * @return string
     */
    public function parentCrumbs(){
        $name = '';
        $parent = $this->parent;
        for($i = 0 ; $i < 30 ; $i++){ // instead while loop
            if ($parent){
                if ($parent->id === $parent->parent_id){
                    throw new \InvalidArgumentException("Category ID is equal to parent category ID ($parent->id).");
                }
                $name = sprintf('%s%s%s', $parent->name, !empty($name) ? '/' : '', $name);
                $parent = $parent->parent;
            } else {
                break;
            }
        }
        return $name;
    }
}
