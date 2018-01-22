<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

use App\Filter;

class ProcessFilter implements ShouldQueue
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

        $page = $filter->snapshot->pages()->offset($filter->scanned)->first();

        $crawler = new Crawler($page->html);

        $values = $filter->values;

        foreach ($crawler->filter($filter->selector) as $domElement) {
            $values[] = $domElement->nodeValue;
        }

        $filter->values = $values;

        $filter->scanned++;

        $filter->save();

        // queue next job
        if (!$filter->isCompleted()) {
            static::dispatch($filter);
        }
    }
}
