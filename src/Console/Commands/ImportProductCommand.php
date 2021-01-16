<?php

namespace AwemaPL\Storage\Console\Commands;

use AwemaPL\Storage\User\Sections\Sources\Jobs\ImportProductJob;
use AwemaPL\Storage\User\Sections\Sources\Models\Source;
use Illuminate\Console\Command;

class ImportProductCommand extends Command
{
    /** @var string The name and signature of the console command. */
    protected $signature = 'storage:import-product {--source_id=}';

    /** @var string The console command description. */
    protected $description = 'Add job for import products to the source.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = Source::find($this->option('source_id'));
        dispatch(new ImportProductJob($source, []));
    }

}
