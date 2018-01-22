<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Snapshot;

class PopulateFilter implements ShouldQueue
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

        if ($cssSelector) {
            $pages = $snapshot->pages;

            foreach ($pages as $page) {
                $crawler = new Crawler($page->html);

                foreach ($crawler->filter('body > p') as $domElement) {
                    $values[] = $domElement->nodeValue;
                }
            }
        }

        // queue next job
        if (!$snapshot->isCompleted()) {
            static::dispatch($snapshot);
        }
    }
}
