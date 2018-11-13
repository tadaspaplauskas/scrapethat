<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\DomCrawler\Crawler;

use App\Models\Variable;

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

        $page = $variable->snapshot->pages()->offset($variable->current_page)->first();

        $crawler = new Crawler($page->html);

        $values = [];

        foreach ($crawler->filter($variable->selector) as $domElement) {
            // parse as number
            if ($variable->isNumeric()) {
                $value = floatval(preg_replace('/[^\d.-]/', '', $domElement->nodeValue));
;
            }
            else {
                $value = $domElement->nodeValue;
            }

            $values[] = [
                'page_id' => $page->id,
                'value' => $value,
            ];
        }

        // create multiple values at once
        $response = $variable->values()->createMany($values);

        $variable->current_page++;

        $variable->save();

        // queue next job
        if (!$variable->isCompleted()) {
            static::dispatch($variable);
        }
    }
}
