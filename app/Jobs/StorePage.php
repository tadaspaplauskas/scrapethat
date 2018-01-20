<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DomToArray\Client;

use App\Snapshot;

class StorePage implements ShouldQueue
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

        $url = $snapshot->nextPageUrl();

        // we're done here
        if (!$url) {
            return;
        }

        $client = new Client($url);

        $dom = $client->array();

        $page = $snapshot->pages()->create([
            'url' => $url,
            'dom' => $dom,
        ]);

        $snapshot->crawled++;

        $snapshot->save();

        // queue next page
        if (!$snapshot->isCompleted()) {
            static::dispatch($snapshot);
        }
    }
}
