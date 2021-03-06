<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use App\Models\Snapshot;
use App\Notifications\DownloadPageProblem;

class DownloadPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $snapshot;
    protected $driver;

    protected $userAgents = [
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0',
        'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.84 Safari/537.36',
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_2) AppleWebKit/604.4.7 (KHTML, like Gecko) Version/11.0.2 Safari/604.4.7',
        'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
    ];

    const CONNECT_TIMEOUT = 10;
    const DELAY_MINUTES = 1;

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

        // deleted, stopped or done
        if (!$snapshot || $snapshot->isStopped() || $snapshot->isCompleted()) {
            return;
        }

        $url = $snapshot->nextPageUrl();

        $guzzle = new Client();

        try {
            $response = $guzzle->request('GET', $url, [
                'timeout' => self::CONNECT_TIMEOUT,
                'connect_timeout' => self::CONNECT_TIMEOUT,
                'headers' => [
                    'User-Agent' => $this->userAgent(),
                ],
            ]);

        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $html = $response->getBody()->getContents();
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();

        $page = $snapshot->pages()->create([
            'url' => $url,
            'status_code' => $statusCode,
            'reason_phrase' => $reasonPhrase,
            'html' => $html,
        ]);

        // notify user, stop
        $success = mb_substr($statusCode, 0, 1) === '2';
        if (!$success) {
            return $snapshot->user->notify(new DownloadPageProblem($page));
        }

        // refresh variables
        if ($snapshot->isCompleted()) {
            foreach ($snapshot->variables as $variable) {
                $variable->process();
            }
        }
        // queue next page
        else {
            static::dispatch($snapshot)->delay(now()->addMinutes(self::DELAY_MINUTES));
        }
    }

    private function userAgent()
    {
        return array_rand($this->userAgents);
    }
}
