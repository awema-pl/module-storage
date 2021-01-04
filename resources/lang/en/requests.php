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
        ]
    ]
];
