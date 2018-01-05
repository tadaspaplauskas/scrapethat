<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Goutte\Client;

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

        $nodes = $this->nodes($snapshot);

        $tree = $this->arrayTree($nodes);

        print_r($tree);
    }

    protected function nodes(Snapshot $snapshot)
    {
        $client = new Client();

        $nodes = $client->request('GET', $snapshot->url);

        return $nodes;
    }

    protected function arrayTree($nodes)
    {
        $tree = [];

        foreach ($nodes as $node) {
            if ($node instanceof \DOMElement) {

                $attributes = [];
                foreach ($node->attributes as $attribute) {
                    $attributes[$attribute->name] = $attribute->value;
                }

                $tree[] = [
                    'tag' => $node->tagName,
                    'attributes' => $attributes,
                    'text' => trim($node->textContent),
                    'children' => $this->arrayTree($node->childNodes)
                ];
            }
        }

        return $tree;
    }
}
