<?php

namespace AwemaPL\Storage;
use AwemaPL\Storage\User\Sections\Categories\Models\Category;
use AwemaPL\Storage\User\Sections\Categories\Policies\CategoryPolicy;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Policies\DuplicateProductPolicy;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Models\CategoryProduct;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Policies\CategoryProductPolicy;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Repositories\Contracts\CategoryProductRepository;
use AwemaPL\Storage\User\Sections\CategoriesProducts\Repositories\EloquentCategoryProductRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Models\Description;
use AwemaPL\Storage\User\Sections\Descriptions\Policies\DescriptionPolicy;
use AwemaPL\Storage\User\Sections\Descriptions\Repositories\Contracts\DescriptionRepository;
use AwemaPL\Storage\User\Sections\Descriptions\Repositories\EloquentDescriptionRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Models\DuplicateProduct;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\Contracts\DuplicateProductRepository;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Repositories\EloquentDuplicateProductRepository;
use AwemaPL\Storage\User\Sections\Features\Models\Feature;
use AwemaPL\Storage\User\Sections\Features\Policies\FeaturePolicy;
use AwemaPL\Storage\User\Sections\Features\Repositories\Contracts\FeatureRepository;
use AwemaPL\Storage\User\Sections\Features\Repositories\EloquentFeatureRepository;
use AwemaPL\Storage\User\Sections\Images\Models\Image;
use AwemaPL\Storage\User\Sections\Images\Policies\ImagePolicy;
use AwemaPL\Storage\User\Sections\Images\Repositories\Contracts\ImageRepository;
use AwemaPL\Storage\User\Sections\Images\Repositories\EloquentImageRepository;
use AwemaPL\Storage\User\Sections\Manufacturers\Models\Manufacturer;
use AwemaPL\Storage\User\Sections\Manufacturers\Policies\ManufacturerPolicy;
use AwemaPL\Storage\User\Sections\Products\Models\Product;
use AwemaPL\Storage\User\Sections\Products\Policies\ProductPolicy;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use AwemaPL\Storage\User\Sections\Sources\Policies\SourcePolicy;
use AwemaPL\Storage\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Storage\User\Sections\Sources\Repositories\EloquentSourceRepository;
use AwemaPL\Storage\User\Sections\Variants\Models\Variant;
use AwemaPL\Storage\User\Sections\Variants\Policies\VariantPolicy;
use AwemaPL\Storage\User\Sections\Variants\Repositories\Contracts\VariantRepository;
use AwemaPL\Storage\User\Sections\Variants\Repositories\EloquentVariantRepository;
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
use AwemaPL\Storage\User\Sections\Descriptions\Services\Contracts\DescriptionType as DescriptionTypeContract;
use AwemaPL\Storage\User\Sections\Descriptions\Services\DescriptionType;
use AwemaPL\Storage\User\Sections\Features\Services\Contracts\FeatureType as FeatureTypeContract;
use AwemaPL\Storage\User\Sections\Features\Services\FeatureType;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoImporter as XmlceneoImporterContract;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\XmlceneoImporter;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\Contracts\XmlceneoUpdater as XmlceneoUpdaterContract;
use AwemaPL\Storage\Sourceables\Sections\Xmlceneo\Services\XmlceneoUpdater;

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
        CategoryProduct::class=>CategoryProductPolicy::class,
        Description::class => DescriptionPolicy::class,
        Variant::class=>VariantPolicy::class,
        Image::class =>ImagePolicy::class,
        Feature::class=>FeaturePolicy::class,
        Source::class =>SourcePolicy::class,
        DuplicateProduct::class =>DuplicateProductPolicy::class,
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
        $this->app->bind(DescriptionRepository::class, EloquentDescriptionRepository::class);
        $this->app->bind(VariantRepository::class, EloquentVariantRepository::class);
        $this->app->bind(ImageRepository::class, EloquentImageRepository::class);
        $this->app->bind(FeatureRepository::class, EloquentFeatureRepository::class);
        $this->app->bind(SourceRepository::class, EloquentSourceRepository::class);
        $this->app->bind(DuplicateProductRepository::class, EloquentDuplicateProductRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind(AvailabilityContract::class, Availability::class);
        $this->app->bind(DescriptionTypeContract::class, DescriptionType::class);
        $this->app->bind(FeatureTypeContract::class, FeatureType::class);
        $this->app->bind(XmlceneoImporterContract::class, XmlceneoImporter::class);
        $this->app->bind(XmlceneoUpdaterContract::class, XmlceneoUpdater::class);
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
