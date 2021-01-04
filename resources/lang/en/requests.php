<?php

return [
    "user" => [
        "warehouse" => [
            "attributes" => [
                "name" => "Name"
            ]
        ],
        "category" => [
            "messages" => [
                "current_category_not_be_parent_category" => "The current category cannot be a parent category."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "name" => "name",
                "parent_id" => "Parent category",
                "external_id" => "External ID"
            ]
        ],
        "manufacturer" => [
            "messages" => [
                "manufacturer_already_exist" => "This manufacturer already exists."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "name" => "name",
                "image_url" => "image web address"
            ]
        ],
        "product" => [
            "messages" => [
                "number_outside_range" => "The given number is outside the allowed range."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "name" => "name",
                "ean" => "EAN",
                "sku" => "SKU",
                "stock" => "stock",
                "availability" => "availability",
                "brutto_price" => "brutto price",
                "tax_rate" => "tax rate",
                "external_id" => "external ID",
                "category_id" => "category",
                "manufacturer_id" => "manufacturer",
                "default_category_id" => "default category"
            ]
        ],
        "category_product" => [
            "messages" => [
                "category_product_already_exist" => "This product is already in the category."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "category_id" => "category",
                "product_id" => "product"
            ]
        ]
    ]
];
