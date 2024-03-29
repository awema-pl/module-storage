<?php

namespace AwemaPL\Storage\User\Sections\Sources\Models;

use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use AwemaPL\Task\User\Sections\Statuses\Models\Contracts\Taskable;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;

class Source extends Model implements SourceContract, Taskable
{

    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'warehouse_id','sourceable_type', 'sourceable_id', 'settings'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' =>'integer',
        'warehouse_id' =>'integer',
        'sourceable_id' =>'integer',
        'settings' =>'array',
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
        return config('storage.database.tables.storage_sources');
    }

    /**
     * Get the owning sourceable model.
     */
    public function sourceable()
    {
        return $this->morphTo();
    }

    /**
     * Get the warehouse that owns the source.
     */
    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }


    /**
     * Category tree
     *
     * @return void
     */
    public function categoryTree()
    {
        return Category::where('source_id', $this->getKey())
            ->with('children')
            ->whereNull('parent_id')
            ->get(['id', 'name', 'parent_id', 'source_id']);
    }

}
