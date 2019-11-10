<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Snapshot;
use App\Jobs\DownloadPage;
use App\Services\QueryProxy;
use DB;
use Auth;
use Response;
use Cache;

class RunQueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function __invoke(Request $request)
    {
        $snapshots = Auth::user()->snapshots;

        $proxy = new QueryProxy();

        foreach ($snapshots as $snapshot) {

            if (!$snapshot->isCompleted()) {
                return Response::json(['error' => 'Snapshots are not available until they\'re completed.'], 423);
            }

            $fieldsCacheKey = md5('f' . $snapshot->id . $snapshot->updated_at . $snapshot->refreshed_at);
            $dumpCacheKey = md5('d' . $snapshot->id . $snapshot->updated_at . $snapshot->refreshed_at);

            $fields = Cache::get($fieldsCacheKey) ?? [];
            $dump = Cache::get($dumpCacheKey) ?? [];

            if (empty($fields) || empty($dump)) {
                foreach ($snapshot->variables as $variable) {

                    if (!$variable->isCompleted()) {
                        return Response::json(['error' => 'Variables are being processed, please wait.'], 423);
                    }

                    // decide column type
                    $fields[$variable->name] = $variable->isNumeric() ? 'double' : 'text';

                    $counter = 0;
                    $variable->values()->chunk(1000, function ($values) use (&$variable, &$dump, &$counter) {
                        foreach ($values as $value) {
                            $dump[$counter][$variable->name] = $value->value;
                            $counter++;
                        }
                    });
                }

                Cache::put($fieldsCacheKey, $fields, 60);
                Cache::put($dumpCacheKey, $dump, 60);
            }

            $proxy->table($snapshot->key, $fields);

            foreach ($dump as $line) {
                $proxy->insert($snapshot->key, $line);
            }
        }

        try {
            $results = $proxy->query($request->input('query'));
        }
        catch (\InvalidArgumentException $e) {
            return Response::json(['errors' => [$e->getMessage()]], 422);
        }

        return Response::json([
            'meta' => [
                'count' => count($results),
            ],
            'data' => $results,
        ]);
    }
}
