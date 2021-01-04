<?php

return [
    'merge_to_navigation' => true,

    'navs' => [
        'sidebar' =>[
            [
                'name' => 'Storage',
                'link' => '/storage/warehouses',
                'icon' => 'speed',
                'key' => 'storage::menus.storage',
                'children_top' => [
                    [
                        'name' => 'Warehouses',
                        'link' => '/storage/warehouses',
                        'key' => 'storage::menus.warehouses',
                    ],
                    [
                        'name' => 'Categories',
                        'link' => '/storage/categories',
                        'key' => 'storage::menus.categories',
                    ],
                    [
                        'name' => 'Manufacturers',
                        'link' => '/storage/manufacturers',
                        'key' => 'storage::menus.manufacturers',
                    ],
                    [
                        'name' => 'Products',
                        'link' => '/storage/products',
                        'key' => 'storage::menus.products',
                    ],
                    [
                        'name' => 'Products in categories',
                        'link' => '/storage/categories-products',
                        'key' => 'storage::menus.categories_products',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Warehouses',
                        'link' => '/storage/warehouses',
                        'key' => 'storage::menus.warehouses',
                    ],
                    [
                        'name' => 'Categories',
                        'link' => '/storage/categories',
                        'key' => 'storage::menus.categories',
                    ],
                    [
                        'name' => 'Manufacturers',
                        'link' => '/storage/manufacturers',
                        'key' => 'storage::menus.manufacturers',
                    ],
                    [
                        'name' => 'Products',
                        'link' => '/storage/products',
                        'key' => 'storage::menus.products',
                    ],
                    [
                        'name' => 'Products in categories',
                        'link' => '/storage/categories-products',
                        'key' => 'storage::menus.categories_products',
                    ],

                ],
            ]
        ],
        'adminSidebar' =>[
            [
                'name' => 'Settings',
                'link' => '/admin/storage/settings',
                'icon' => 'speed',
                'permissions' => 'manage_storage_settings',
                'key' => 'storage::menus.storage',
                'children_top' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/storage/settings',
                        'key' => 'storage::menus.settings',
                    ],
                ],
                'children' => [
                    [
                        'name' => 'Settings',
                        'link' => '/admin/storage/settings',
                        'key' => 'storage::menus.settings',
                    ],
                ],
            ]
        ]
    ]
];
