<?php

namespace AwemaPL\Storage\User\Sections\Categories\Models;

use AwemaPL\ProductIntegrator\User\Sections\CategoryMappings\Models\CategoryMapping;
use AwemaPL\Storage\Common\Exceptions\StorageException;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Storage\User\Sections\Categories\Models\Contracts\Category as CategoryContract;
use Illuminate\Support\Collection;

class Category extends Model implements CategoryContract
{
    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [];

    /** @var int $integrationId */
    public static $integrationId;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'warehouse_id', 'name', 'parent_id', 'source_id', 'external_id',
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
        'source_id' => 'integer',
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
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        self::updating(function (Model $model) {
            if ($model->getKey() === $model->parent_id) {
                throw new StorageException('Category and parent category cannot be the same.', StorageException::ERROR_SAME_VALUES, 409, null,
                    _p('storage::exceptions.user.category.category_and_parent_category_not_same', 'Category and parent category cannot be the same.'), null, false);
            }
        });
    }

    /**
     * Get the user that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
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
     * Get the source that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    /**
     * Get the product integrator category mappings that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productIntegratorCategoryMappings()
    {
        return $this->hasOne(CategoryMapping::class, 'category_id');
    }

    /**
     * Get the parent category that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the my children category that owns the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    /**
     * Recursive, loads all descendants
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tree()
    {
        return $this->children()->select(['id', 'name', 'parent_id', 'source_id'])->with(['tree' => function ($query) {
            $query->select(['id', 'name', 'parent_id', 'source_id']);
        }, 'productIntegratorCategoryMappings' => function ($query) {
            $query->select(['id', 'target_category_id', 'include_subcategories', 'category_id']);
            $query->where('integration_id', static::$integrationId);
        }]);

    }

    /**
     * Recursive, loads all descendants
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public static function getTree(int $integrationId, int $sourceId)
    {
        static::$integrationId = $integrationId;
        return Category::where('source_id', $sourceId)
            ->with(['tree', 'productIntegratorCategoryMappings' => function ($query) use (&$integrationId) {
                $query->select(['id', 'target_category_id', 'include_subcategories', 'category_id', 'integration_id']);
                $query->where('integration_id', $integrationId);
            }])
            ->whereNull('parent_id')
            ->get(['id', 'name', 'parent_id', 'source_id'])
            ->toArray();
    }

    /**
     * Get crumbs
     *
     * @return string
     */
    public function crumbs()
    {
        $parentCrumbs = $this->parentCrumbs();
        $parentCrumbs .= !empty($parentCrumbs) ? '/' : '';
        return $parentCrumbs . $this->name;
    }

    /**
     * Get parent crumbs
     *
     * @return string
     */
    public function parentCrumbs()
    {
        return $this->getParentCategories()->reduce(function ($crumbs, $parentCategory) {
            return sprintf('%s%s%s', $parentCategory->name, !empty($crumbs) ? '/' : '', $crumbs);
        }, '');
    }

    /**
     * Get the products for the category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, config('storage.database.tables.storage_category_product'));
    }

    /**
     * Get parent categories
     *
     * @return Collection
     */
    public function getParentCategories()
    {
        $parentCategories = collect();
        $parent = $this->parent;
        for ($i = 0; $i < 30; $i++) { // instead while loop
            if ($parent) {
                if ($parent->id === $parent->parent_id) {
                    throw new \InvalidArgumentException("Category ID is equal to parent category ID ($parent->id).");
                }
                $parentCategories->push($parent);
                $parent = $parent->parent;
            } else {
                break;
            }
        }
        return $parentCategories;
    }

}
