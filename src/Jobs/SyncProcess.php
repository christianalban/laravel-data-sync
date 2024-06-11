<?php

namespace Alban\LaravelDataSync\Jobs;

use Alban\LaravelDataSync\Support\Synchronizer\Synchronizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class SyncProcess implements ShouldQueue, ShouldBeUnique
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $item,
        public string $uniqueJobId,
        public string $action,
        public Synchronizer $sync,
    ) {}

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return $this->uniqueJobId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->action === 'create') {
            $this->sync->createSync($this->item);
        }

        if ($this->action === 'update') {
            $this->sync->updateSync($this->item);
        }

        if ($this->action === 'delete') {
            $this->sync->deleteSync($this->item);
        }
    }
}
