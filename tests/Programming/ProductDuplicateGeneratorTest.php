<?php

namespace AwemaPL\Storage\Tests\Programming;

use AwemaPL\Storage\User\Sections\Products\Models\Product;
use Tests\TestCase;
use AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts\ProductDuplicateGenerator;

class ProductDuplicateGeneratorTest extends TestCase
{
    public function testSearchDuplicates()
    {
        /** @var ProductDuplicateGenerator $generator */
        $generator = app(ProductDuplicateGenerator::class);

        $product = Product::where('external_id', 'CKY789')->first();
        $generator->generate($product);
    }
}
