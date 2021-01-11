<?php

return [
    "user" => [
        "warehouse" => [
            "attributes" => [
                "name" => "Name",
                "duplicate_products" => [
                    "external_id" => "generate duplicate products via external ID",
                    "gtin" => "generate duplicate products via GTIN"
                ]
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
                "gtin" => "GTIN",
                "sku" => "SKU",
                "stock" => "stock",
                "availability" => "availability",
                "brutto_price" => "brutto price",
                "tax_rate" => "tax rate",
                "external_id" => "external ID",
                "category_id" => "category",
                "manufacturer_id" => "manufacturer",
                "default_category_id" => "default category",
                "active" => "active"
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
        ],
        "description" => [
            "messages" => [
                "type_already_exists" => "This description type already exists."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "product_id" => "product",
                "type" => "type",
                "value" => "description"
            ]
        ],
        "variant" => [
            "messages" => [
                "number_outside_range" => "The given number is outside the allowed range."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "product_id" => "product",
                "name" => "name",
                "gtin" => "GTIN",
                "sku" => "SKU",
                "stock" => "stock",
                "availability" => "availability",
                "brutto_price" => "brutto price",
                "external_id" => "external ID"
            ]
        ],
        "image" => [
            "attributes" => [
                "warehouse_id" => "warehouse",
                "product_id" => "product",
                "variant_id" => "variant",
                "url" => "Web address",
                "external_id" => "external ID",
                "main" => "Main"
            ]
        ],
        "feature" => [
            "messages" => [
                "feature_already_exist" => "This feature type already exists."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "product_id" => "product",
                "variant_id" => "variant",
                "name" => "name",
                "value" => "feature",
                "type" => "type"
            ]
        ],
        "source" => [
            "messages" => [
                "source_already_exist" => "This source already exists.",
                "number_outside_range" => "The given number is outside the allowed range."
            ],
            "attributes" => [
                "warehouse_id" => "warehouse",
                "sourceable_type" => "source type",
                "sourceable_id" => "source",
                "stock" => "stock",
                "availability" => "availability",
                "brutto_price" => "brutto price",
                "settings" => [
                    "manufacturer_attribute_name" => "manufacturer attribute name",
                    "default_tax_rate" => "default tax rate"
                ]
            ]
        ],
        "duplicate_product" => [
            "attributes" => [
                "warehouse_id" => "warehouse",
                "duplicate_product_id" => "duplicate product"
            ]
        ]
    ]
];
