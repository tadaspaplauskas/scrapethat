<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Snapshot;
use App\Jobs\DownloadPage;
use App\Services\QueryProxy;
use DB;

class SnapshotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function query(Request $request, Snapshot $snapshot)
    {
        if (!$snapshot->isCompleted()) {
            return response()->json(['error' => 'Snapshot is not available until it\'s completed.'], 423);
        }

        $dump = [];
        
        foreach ($snapshot->variables as $variable) {
            $counter = 0;

            $variable->values()->chunk(1000, function ($values) use (&$variable, &$dump, &$counter) {
                foreach ($values as $value) {
                    $dump[$counter][$variable->name] = $value->value;
                    $counter++;
                }
            });
        }

        $proxy = new QueryProxy($snapshot->variables->pluck('name'));

        foreach ($dump as $line) {
            $proxy->insert($line);
        }

        try {
            $results = $proxy->query($request->input('query'));
        } catch (\InvalidArgumentException $e) {
            return response()->json(['errors' => [$e->getMessage()]], 422);
        }

        return response()->json([
            'meta' => [
                'count' => count($results),
            ],
            'data' => $results,
        ]);
    }
}
