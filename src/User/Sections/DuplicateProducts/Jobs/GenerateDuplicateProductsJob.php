<?php

namespace AwemaPL\Storage\User\Sections\DuplicateProducts\Jobs;

use AwemaPL\Storage\User\Sections\DuplicateProducts\Services\Contracts\ProductDuplicateGenerator;
use AwemaPL\Storage\User\Sections\Warehouses\Models\Contracts\Warehouse as WarehouseContract;
use AwemaPL\Task\User\Sections\Statuses\Services\TaskStatus;
use AwemaPL\Task\User\Sections\Statuses\Traits\Statusable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateDuplicateProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Statusable;

    const TYPE = 'generate_duplicate_products_in_warehouse';
    const TYPE_KEY = 'storage::jobs.user.duplicate_product.' . self::TYPE;

    /** @var int The number of times the job may be attempted. */
    public $tries = 1;

    /** @var int The number of seconds the job can run before timing out. */
    public $timeout = 3600;

    /** @var bool Delete the job if its models no longer exist. */
    public $deleteWhenMissingModels = true;

    /** @var WarehouseContract $warehouse */
    protected $warehouse;

    /** @var array $options */
    protected $options;

    public function __construct(WarehouseContract $warehouse, array $options = [])
    {
        $this->addStatus(self::TYPE, self::TYPE_KEY, $warehouse, true);
        $this->warehouse = $warehouse;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setTaskStatus(TaskStatus::EXECUTING);
        $generator = $this->getGenerator();
        $generator->generateWarehouse($this->warehouse);
        $this->setTaskStatus(TaskStatus::FINISHED);
    }

    /**
     * Handle a job failure.
     *
     * @param  Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $this->setTaskFailed($exception);
    }

    /**
     * Get generator
     *
     * @return ProductDuplicateGenerator
     */
    private function getGenerator()
    {
        /** @var ProductDuplicateGenerator $generator */
        return app(ProductDuplicateGenerator::class);
    }
}
