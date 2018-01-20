<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte\Client;

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

        $client = new Client();

        $response = $client->request('GET', $url);

        $html = $response->html();

        $page = $snapshot->pages()->create([
            'url' => $url,
            'html' => $html,
        ]);

        $snapshot->crawled++;

        $snapshot->save();

        // queue next page
        if (!$snapshot->isCompleted()) {
            static::dispatch($snapshot);
        }
    }
}
