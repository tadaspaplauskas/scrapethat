<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DOMToArray\Client;

use App\Snapshot;

class CrawlSnapshot implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $snapshot;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Snapshot $snapshot)
    {
        $this->snapshot = $snapshot;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $snapshot = $this->snapshot;

        $client = new Client($snapshot->url);

        $tree = $client->array();

        print_r($tree);
    }
}
