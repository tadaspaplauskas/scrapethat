<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

use App\Variable;

class ProcessVariable implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $variable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Variable $variable)
    {
        $this->variable = $variable;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $variable = $this->variable;

        $page = $variable->snapshot->pages()->offset($variable->scanned)->first();

        $crawler = new Crawler($page->html);

        $values = $variable->values;

        foreach ($crawler->filter($variable->selector) as $domElement) {
            $values[] = $domElement->nodeValue;
        }

        $variable->values = $values;

        $variable->scanned++;

        $variable->save();

        // queue next job
        if (!$variable->isCompleted()) {
            static::dispatch($variable);
        }
    }
}
