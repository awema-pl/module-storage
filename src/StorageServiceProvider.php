<?php

namespace AwemaPL\Storage;


use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\Categories\Policies\CategoryPolicy;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Repositories\Contracts\CategoryProductRepository;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Repositories\EloquentCategoryProductRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Manufacturers\Policies\ManufacturerPolicy;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Products\Policies\ProductPolicy;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Warehouse;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Storage\Admin\Sections\Settings\Repositories\EloquentSettingRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\Contracts\WarehouseRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Repositories\EloquentWarehouseRepository;
use AwemaPL\Storage\User\Sections\Categories\Repositories\Contracts\CategoryRepository;
use AwemaPL\Storage\User\Sections\Categories\Repositories\EloquentCategoryRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\Contracts\ManufacturerRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Repositories\EloquentManufacturerRepository;
use AwemaPL\Storage\User\Sections\Products\Repositories\Contracts\ProductRepository;
use AwemaPL\Storage\User\Sections\Products\Repositories\EloquentProductRepository;
use AwemaPL\Storage\User\Sections\Warehouses\Policies\WarehousePolicy;
use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Storage\Listeners\EventSubscriber;
use AwemaPL\Storage\Admin\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Storage\Admin\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Storage\Admin\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Storage\Admin\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Storage\Contracts\Storage as StorageContract;
use Illuminate\Support\Facades\Event;
use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability as AvailabilityContract;
use AwemaPL\Storage\User\Sections\Products\Services\Availability;

class StorageServiceProvider extends AwemaProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Warehouse::class => WarehousePolicy::class,
        Category::class => CategoryPolicy::class,
        Manufacturer::class=>ManufacturerPolicy::class,
        Product::class =>ProductPolicy::class,
    ];

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'storage');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'storage');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->bootMiddleware();
        app('storage')->includeLangJs();
        app('storage')->menuMerge();
        app('storage')->mergePermissions();
        $this->registerPolicies();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/storage.php', 'storage');
        $this->mergeConfigFrom(__DIR__ . '/../config/storage-menu.php', 'storage-menu');
        $this->app->bind(StorageContract::class, Storage::class);
        $this->app->singleton('storage', StorageContract::class);
        $this->registerRepositories();
        $this->registerServices();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'storage';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(SettingRepository::class, EloquentSettingRepository::class);
        $this->app->bind(WarehouseRepository::class, EloquentWarehouseRepository::class);
        $this->app->bind(CategoryRepository::class, EloquentCategoryRepository::class);
        $this->app->bind(ManufacturerRepository::class, EloquentManufacturerRepository::class);
        $this->app->bind(ProductRepository::class, EloquentProductRepository::class);
        $this->app->bind(CategoryProductRepository::class, EloquentCategoryProductRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind(AvailabilityContract::class, Availability::class);
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('storage', RouteMiddleware::class);
    }

    /**
     * Boot grEloquentAccountRepositoryoup middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
    }
}
