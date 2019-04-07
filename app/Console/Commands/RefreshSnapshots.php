<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Snapshot;

class RefreshSnapshots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snapshot:refresh:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh snapshots daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Snapshot::where('refresh_daily', 1)->cursor() as $snapshot) {
            $snapshot->download();
        }
    }
}
