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
        'manufacturer'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'name' => 'nazwa',
                'image_url' => 'adres www obrazka',
            ],
            'messages'=>[
                'manufacturer_already_exists' =>'Ten producent już istnieje.',
            ]
        ],
        'product'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'default_category_id' => 'kategoria domyślna',
                'manufacturer_id' => 'producent',
                'name' => 'nazwa',
                'ean' => 'EAN',
                'sku' => 'SKU',
                'stock' => 'stan magazynowy',
                'availability' => 'dostępność',
                'brutto_price' => 'cena brutto',
                'tax_rate' => 'stawka podatkowa',
                'external_id'=>'zewnętrzny ID',
            ],
            'messages'=>[
                'number_outside_range' =>'Podana liczba nie mieści się w dozwolonym przedziale.',
            ]
        ],
        'category_product'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'category_id' => 'kategoria',
                'product_id' => 'produkt',
            ],
            'messages'=>[
                'category_product_already_exists' =>'Ten produkt jest już w kategorii.',
            ]
        ],
        'description'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'product_id' => 'produkt',
                'type' => 'typ',
                'value' => 'opis',
            ],
            'messages'=>[
                'type_already_exists' =>'Ten typ opisu już istnieje.',
            ]
        ],
        'variant'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'product_id' => 'produkt',
                'name' => 'nazwa',
                'ean' => 'EAN',
                'sku' => 'SKU',
                'stock' => 'stan magazynowy',
                'availability' => 'dostępność',
                'brutto_price' => 'cena brutto',
                'external_id'=>'zewnętrzny ID',
            ],
            'messages'=>[
                'number_outside_range' =>'Podana liczba nie mieści się w dozwolonym przedziale.',
            ]
        ],
        'image'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'product_id' => 'produkt',
                'variant_id' => 'produkt',
                'url' => 'adres www',
                'external_id'=>'zewnętrzny ID',
            ],
            'messages'=>[]
        ],
        'feature'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'product_id' => 'produkt',
                'variant_id' => 'produkt',
                'type' => 'typ',
                'name'=>'nazwa',
                'value' =>'wartość',
            ],
            'messages'=>[
                'feature_already_exist' =>'Ta cecha już istnieje.',
            ]
        ],
    ],
];
