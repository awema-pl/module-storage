<?php
return [
    // this resources has been auto load to layout
    'dist' => [
        'js/main.js',
        'js/main.legacy.js',
        'css/main.css',
    ],
    'routes' => [

        // all routes is active
        'active' => true,

        // Administrator section.
        'admin' => [
            // section installations
            'installation' => [
                'active' => true,
                'prefix' => '/installation/storage',
                'name_prefix' => 'storage.admin.installation.',
                // this routes has beed except for installation module
                'expect' => [
                    'module-assets.assets',
                    'storage.admin.installation.index',
                    'storage.admin.installation.store',
                ]
            ],
            'setting' => [
                'active' => true,
                'prefix' => '/admin/storage/settings',
                'name_prefix' => 'storage.admin.setting.',
                'middleware' => [
                    'web',
                    'auth',
                    'can:manage_storage_settings'
                ]
            ],
        ],

        // User section
        'user' => [
            'warehouse' => [
                'active' => true,
                'prefix' => '/storage/warehouses',
                'name_prefix' => 'storage.user.warehouse.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
            'category' => [
                'active' => true,
                'prefix' => '/storage/categories',
                'name_prefix' => 'storage.user.category.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
            'manufacturer' => [
                'active' => true,
                'prefix' => '/storage/manufacturers',
                'name_prefix' => 'storage.user.manufacturer.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
            'product' => [
                'active' => true,
                'prefix' => '/storage/products',
                'name_prefix' => 'storage.user.product.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
            'category_product' => [
                'active' => true,
                'prefix' => '/storage/categories-products',
                'name_prefix' => 'storage.user.category_product.',
                'middleware' => [
                    'web',
                    'auth',
                    'verified'
                ]
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Use permissions in application.
    |--------------------------------------------------------------------------
    |
    | This permission has been insert to database with migrations
    | of module permission.
    |
    */
    'permissions' =>[
        'install_packages', 'manage_storage_settings',
    ],

    /*
    |--------------------------------------------------------------------------
    | Can merge permissions to module permission
    |--------------------------------------------------------------------------
    */
    'merge_permissions' => true,

    'installation' => [
        'auto_redirect' => [
            // user with this permission has been automation redirect to
            // installation package
            'permission' => 'install_packages'
        ]
    ],

    'database' => [
        'tables' => [
            'users' => 'users',
            'storage_settings' => 'storage_settings',
            'storage_warehouses' =>'storage_warehouses',
            'storage_products' => 'storage_products',
            'storage_categories' => 'storage_categories',
            'storage_category_product' => 'storage_category_product',
            'storage_manufacturers' => 'storage_manufacturers',
            'storage_descriptions' => 'storage_descriptions',
            'storage_images' => 'storage_images',
            'storage_features' => 'storage_features',
            'storage_variants' => 'storage_variants',
        ]
    ],
];
