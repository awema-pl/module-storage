<?php

namespace AwemaPL\Storage\User\Sections\Sources\Jobs;

use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Source as SourceContract;
use AwemaPL\Task\User\Sections\Statuses\Services\TaskStatus;
use AwemaPL\Task\User\Sections\Statuses\Traits\Statusable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class UpdateProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Statusable;

    const TYPE = 'update_products_to_warehouse';
    const TYPE_KEY = 'storage::jobs.user.source.' . self::TYPE;
    
    /** @var int The number of times the job may be attempted. */
    public $tries = 1;

    /** @var int The number of seconds the job can run before timing out. */
    public $timeout = 72000;

    /** @var bool Delete the job if its models no longer exist. */
    public $deleteWhenMissingModels = true;

    /** @var SourceContract $source */
    protected $source;

    /** @var array $options */
    protected $options;

    public function __construct(SourceContract $source, array $options = [])
    {
        $this->addStatus(self::TYPE, self::TYPE_KEY, $source, true);
        $this->source = $source;
        $this->options = $options;
    }

    public function handle()
    {
        $this->setTaskStatus(TaskStatus::EXECUTING);
        $this->source->sourceable->updateProducts($this->source, $this->options);
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
}
