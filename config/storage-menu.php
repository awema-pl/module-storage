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
