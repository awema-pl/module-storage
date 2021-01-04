<?php

return [
    'admin' =>[
        'installation' => [
            'attributes' =>[
            ],
            'messages' =>[

            ],
        ],
        'setting'=>[
            'attributes' => [
                'key' => 'klucz',
                'value' => 'wartość',
            ],
            'messages'=>[

            ]
        ],
    ],
    'user' =>[
        'warehouse'=>[
            'attributes' => [
                'name' => 'nazwa',
            ],
            'messages'=>[]
        ],
        'category'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'name' => 'nazwa',
                'external_id' => 'zewnętrzny identyfikator',
                'parent_id' =>'kategoria nadrzędna',
            ],
            'messages'=>[
                'current_category_not_be_parent_category' => 'Bieżąca kategoria nie może być kategorią rodzica.',
            ]
        ],
    ],
];
