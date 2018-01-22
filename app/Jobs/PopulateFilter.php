<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Filter;

class PopulateFilter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filter;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filter = $this->filter;

        $page = $filter->snapshot->pages()->offset($filter->scanned_pages)->first();

        $crawler = new Crawler($page->html);

        foreach ($crawler->filter($filter->selector) as $domElement) {
            $filter->values[] = $domElement->nodeValue;
        }

        $filter->scanned_pages++;

        $filter->save();

        // queue next job
        if (!$filter->isCompleted()) {
            static::dispatch($filter);
        }
    }
}
