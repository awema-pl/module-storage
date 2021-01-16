<?php

namespace AwemaPL\Storage\Console\Commands;

use AwemaPL\Storage\User\Sections\Sources\Jobs\UpdateProductJob;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use Illuminate\Console\Command;

class UpdateProductCommand extends Command
{
    /** @var string The name and signature of the console command. */
    protected $signature = 'storage:update-product {--source_id=}';

    /** @var string The console command description. */
    protected $description = 'Add job for update products to the source.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = Source::find($this->option('source_id'));
        dispatch(new UpdateProductJob($source, [
            'stock' => true,
            'availability' => true,
            'brutto_price' => true,
        ]));
    }

}
