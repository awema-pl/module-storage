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
            if ($this->isActiveUserWarehouseRoutes()) {
                $this->userWarehouseRoutes();
            }
            if ($this->isActiveUserCategoryRoutes()) {
                $this->userCategoryRoutes();
            }
            if ($this->isActiveAdminSettingRoutes()) {
                $this->adminSettingRoutes();
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
