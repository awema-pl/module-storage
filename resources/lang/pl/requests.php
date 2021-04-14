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
                'duplicate_product_settings' => [
                    'external_id' =>'generuj duplikaty produktów przez zewnętrzny ID',
                    'gtin' =>'generuj duplikaty produktów przez GTIN',
                ]
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
                'active' => 'aktywny',
                'name' => 'nazwa',
                'gtin' => 'GTIN',
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
                'active' => 'aktywny',
                'name' => 'nazwa',
                'gtin' => 'GTIN',
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
                'main' => 'Główny',
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
        'source'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'sourceable_type' => 'typ źródła',
                'sourceable_id' => 'źródło',
                'stock' => 'stan magazynowy',
                'availability'=> 'dostępność',
                'brutto_price' =>'cena brutto',
                'name' => 'nazwa',
                'description' => 'opis',
                'features' => 'cechy',
                'settings' => [
                    'manufacturer_attribute_name' =>'nazwa atrybutu producenta',
                    'default_tax_rate' =>'domyślna stawka podatku',
                ]
            ],
            'messages'=>[
                'source_already_exist' =>'To źródło już istnieje.',
                'number_outside_range' =>'Podana liczba nie mieści się w dozwolonym przedziale.',
            ]
        ],
        'duplicate_product'=>[
            'attributes' => [
                'warehouse_id' => 'magazyn',
                'duplicate_product_id' => 'duplikat produktu',
                'produkt' => 'produkt',
            ],
            'messages'=>[
                'duplicate_product_and_product_not_same' => 'Duplikat produktu i produkt nie mogą być takie same.',
            ]
        ],
    ],
];
