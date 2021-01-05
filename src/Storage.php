<?php

namespace AwemaPL\Storage;

use AwemaPL\Storage\Admin\Sections\Settings\Models\Setting;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use AwemaPL\Storage\Contracts\Storage as StorageContract;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Storage implements StorageContract
{
    /** @var Router $router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;}

    /**
     * Routes
     */
    public function routes()
    {
        if ($this->isActiveRoutes()) {
            if ($this->isActiveAdminInstallationRoutes() && (!$this->isMigrated())) {
                $this->adminInstallationRoutes();
            }
            if ($this->isActiveAdminSettingRoutes()) {
                $this->adminSettingRoutes();
            }
            if ($this->isActiveUserWarehouseRoutes()) {
                $this->userWarehouseRoutes();
            }
            if ($this->isActiveUserCategoryRoutes()) {
                $this->userCategoryRoutes();
            }
            if ($this->isActiveUserManufacturerRoutes()) {
                $this->userManufacturerRoutes();
            }
            if ($this->isActiveUserProductRoutes()) {
                $this->userProductRoutes();
            }
            if ($this->isActiveUserCategoryProductRoutes()) {
                $this->userCategoryProductRoutes();
            }
            if ($this->isActiveUserDescriptionRoutes()) {
                $this->userDescriptionRoutes();
            }
            if ($this->isActiveUserVariantRoutes()) {
                $this->userVariantRoutes();
            }
            if ($this->isActiveUserImageRoutes()) {
                $this->userImageRoutes();
            }
            if ($this->isActiveUserFeatureRoutes()) {
                $this->userFeatureRoutes();
            }
        }
    }

    /**
     * Admin installation routes
     */
    protected function adminInstallationRoutes()
    {
        $prefix = config('storage.routes.admin.installation.prefix');
        $namePrefix = config('storage.routes.admin.installation.name_prefix');
        $this->router->prefix($prefix)->name($namePrefix)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\Admin\Sections\Installations\Http\Controllers\InstallationController@index')
                ->name('index');
            $this->router->post('/', '\AwemaPL\Storage\Admin\Sections\Installations\Http\Controllers\InstallationController@store')
                ->name('store');
        });

    }

    /**
     * Admin setting routes
     */
    protected function adminSettingRoutes()
    {
        $prefix = config('storage.routes.admin.setting.prefix');
        $namePrefix = config('storage.routes.admin.setting.name_prefix');
        $middleware = config('storage.routes.admin.setting.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\Admin\Sections\Settings\Http\Controllers\SettingController@index')
                ->name('index');
            $this->router
                ->get('/settings', '\AwemaPL\Storage\Admin\Sections\Settings\Http\Controllers\SettingController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\Admin\Sections\Settings\Http\Controllers\SettingController@update')
                ->name('update');
        });
    }

    /**
     * User warehouse routes
     */
    protected function userWarehouseRoutes()
    {
        $prefix = config('storage.routes.user.warehouse.prefix');
        $namePrefix = config('storage.routes.user.warehouse.name_prefix');
        $middleware = config('storage.routes.user.warehouse.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@store')
                ->name('store');
            $this->router
                ->get('/warehouses', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@delete')
                ->name('delete');
            $this->router
                ->get('/select-warehouse-id', '\AwemaPL\Storage\User\Sections\Warehouses\Http\Controllers\WarehouseController@selectWarehouseId')
                ->name('select_warehouse_id');
        });
    }

    /**
     * User category routes
     */
    protected function userCategoryRoutes()
    {
        $prefix = config('storage.routes.user.category.prefix');
        $namePrefix = config('storage.routes.user.category.name_prefix');
        $middleware = config('storage.routes.user.category.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@store')
                ->name('store');
            $this->router
                ->get('/categories', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@delete')
                ->name('delete');
            $this->router
                ->get('/select-category-id', '\AwemaPL\Storage\User\Sections\Categories\Http\Controllers\CategoryController@selectCategoryId')
                ->name('select_category_id');
        });
    }

    /**
     * User product routes
     */
    protected function userProductRoutes()
    {
        $prefix = config('storage.routes.user.product.prefix');
        $namePrefix = config('storage.routes.user.product.name_prefix');
        $middleware = config('storage.routes.user.product.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@store')
                ->name('store');
            $this->router
                ->get('/products', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@delete')
                ->name('delete');
            $this->router
                ->get('/select-availability', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@selectAvailability')
                ->name('select_availability');
            $this->router
                ->get('/select-product-id', '\AwemaPL\Storage\User\Sections\Products\Http\Controllers\ProductController@selectProductId')
                ->name('select_product_id');
        });
    }

    /**
     * User category product routes
     */
    protected function userCategoryProductRoutes()
    {
        $prefix = config('storage.routes.user.category_product.prefix');
        $namePrefix = config('storage.routes.user.category_product.name_prefix');
        $middleware = config('storage.routes.user.category_product.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers\CategoryProductController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers\CategoryProductController@store')
                ->name('store');
            $this->router
                ->get('/products', '\AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers\CategoryProductController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers\CategoryProductController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\CategoriesProducts\Http\Controllers\CategoryProductController@delete')
                ->name('delete');
        });
    }

    /**
     * User manufacturer routes
     */
    protected function userManufacturerRoutes()
    {
        $prefix = config('storage.routes.user.manufacturer.prefix');
        $namePrefix = config('storage.routes.user.manufacturer.name_prefix');
        $middleware = config('storage.routes.user.manufacturer.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@store')
                ->name('store');
            $this->router
                ->get('/manufacturers', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@delete')
                ->name('delete');
            $this->router
                ->get('/select-manufacturer-id', '\AwemaPL\Storage\User\Sections\Manufacturers\Http\Controllers\ManufacturerController@selectManufacturerId')
                ->name('select_manufacturer_id');
        });
    }

    /**
     * User description routes
     */
    protected function userDescriptionRoutes()
    {
        $prefix = config('storage.routes.user.description.prefix');
        $namePrefix = config('storage.routes.user.description.name_prefix');
        $middleware = config('storage.routes.user.description.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@store')
                ->name('store');
            $this->router
                ->get('/descriptions', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@delete')
                ->name('delete');
            $this->router
                ->get('/select-type', '\AwemaPL\Storage\User\Sections\Descriptions\Http\Controllers\DescriptionController@selectType')
                ->name('select_type');
        });
    }

    /**
     * User variant routes
     */
    protected function userVariantRoutes()
    {
        $prefix = config('storage.routes.user.variant.prefix');
        $namePrefix = config('storage.routes.user.variant.name_prefix');
        $middleware = config('storage.routes.user.variant.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@store')
                ->name('store');
            $this->router
                ->get('/variants', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@delete')
                ->name('delete');
            $this->router
                ->get('/select-variant-id', '\AwemaPL\Storage\User\Sections\Variants\Http\Controllers\VariantController@selectVariantId')
                ->name('select_variant_id');
        });
    }

    /**
     * User image routes
     */
    protected function userImageRoutes()
    {
        $prefix = config('storage.routes.user.image.prefix');
        $namePrefix = config('storage.routes.user.image.name_prefix');
        $middleware = config('storage.routes.user.image.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Images\Http\Controllers\ImageController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Images\Http\Controllers\ImageController@store')
                ->name('store');
            $this->router
                ->get('/images', '\AwemaPL\Storage\User\Sections\Images\Http\Controllers\ImageController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Images\Http\Controllers\ImageController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Images\Http\Controllers\ImageController@delete')
                ->name('delete');
        });
    }

    /**
     * User feature routes
     */
    protected function userFeatureRoutes()
    {
        $prefix = config('storage.routes.user.feature.prefix');
        $namePrefix = config('storage.routes.user.feature.name_prefix');
        $middleware = config('storage.routes.user.feature.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@store')
                ->name('store');
            $this->router
                ->get('/features', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@update')
                ->name('update');
            $this->router
                ->delete('{id?}', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@delete')
                ->name('delete');
            $this->router
                ->get('/select-type', '\AwemaPL\Storage\User\Sections\Features\Http\Controllers\FeatureController@selectType')
                ->name('select_type');
        });
    }

    /**
     * Can installation
     *
     * @return bool
     */
    public function canInstallation()
    {
        $canForPermission = $this->canInstallForPermission();
        return $this->isActiveRoutes()
            && $this->isActiveAdminInstallationRoutes()
            && $canForPermission
            && (!$this->isMigrated());
    }

    /**
     * Is migrated
     *
     * @return bool
     */
    public function isMigrated()
    {
        $tablesInDb = array_map('reset', DB::select('SHOW TABLES'));

        $tables = array_values(config('storage.database.tables'));
        foreach ($tables as $table){
            if (!in_array($table, $tablesInDb)){
                return false;
            }
        }
        return true;
    }

    /**
     * Is active routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveRoutes()
    {
        return config('storage.routes.active');
    }

    /**
     * Is active admin setting routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public function isActiveAdminSettingRoutes()
    {
        return config('storage.routes.admin.setting.active');
    }

    /**
     * Is active admin installation routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveAdminInstallationRoutes()
    {
        return config('storage.routes.admin.installation.active');
    }

    /**
     * Is active user warehouse routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserWarehouseRoutes()
    {
        return config('storage.routes.user.warehouse.active');
    }

    /**
     * Is active user category routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserCategoryRoutes()
    {
        return config('storage.routes.user.category.active');
    }

    /**
     * Is active user manufacturer routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserManufacturerRoutes()
    {
        return config('storage.routes.user.manufacturer.active');
    }

    /**
     * Is active user product routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserProductRoutes()
    {
        return config('storage.routes.user.product.active');
    }

    /**
     * Is active user category product routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserCategoryProductRoutes()
    {
        return config('storage.routes.user.category_product.active');
    }

    /**
     * Is active user description routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserDescriptionRoutes()
    {
        return config('storage.routes.user.description.active');
    }

    /**
     * Is active user variant routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserVariantRoutes()
    {
        return config('storage.routes.user.variant.active');
    }

    /**
     * Is active user image routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserImageRoutes()
    {
        return config('storage.routes.user.image.active');
    }

    /**
     * Is active user feature routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserFeatureRoutes()
    {
        return config('storage.routes.user.feature.active');
    }

    /**
     * Include lang JS
     */
    public function includeLangJs()
    {
        $lang = config('indigo-layout.frontend.lang', []);
        $lang = array_merge_recursive($lang, app(Translator::class)->get('storage::js')?:[]);
        app('config')->set('indigo-layout.frontend.lang', $lang);
    }

    /**
     * Can install for permission
     *
     * @return bool
     */
    private function canInstallForPermission()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'hasRole')) {
            return true;
        }

        if ($user = request()->user() ?? null){
            return $user->can(config('storage.installation.auto_redirect.permission'));
        }

        return false;
    }

    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()){
            $storageMenu = config('storage-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $storageMenu);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('storage-menu.merge_to_navigation') && self::isMigrated();
    }

    /**
     * Execute package migrations
     */
    public function migrate()
    {
         Artisan::call('migrate', ['--force' => true, '--path'=>'vendor/awema-pl/module-storage/database/migrations']);
    }

    /**
     * Install package
     */
    public function install()
    {
        $this->migrate();
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
       if ($this->canMergePermissions()){
           $storagePermissions = config('storage.permissions');
           $tempPermissions = config('temp_permission.permissions', []);
           $permissions = array_merge_recursive($tempPermissions, $storagePermissions);
           config(['temp_permission.permissions' => $permissions]);
       }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('storage.merge_permissions');
    }
}
