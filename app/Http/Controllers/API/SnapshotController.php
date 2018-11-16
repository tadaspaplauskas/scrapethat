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

class SnapshotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $snapshots = Auth::user()->snapshots()->orderBy('created_at', 'desc')->get();

        return Response::json($snapshots);
    }

    public function show(Snapshot $snapshot)
    {
        return Response::json($snapshot);
    }

    public function store(Request $request)
    {
        $data = $request->validate(Snapshot::validator());

        $snapshot = Auth::user()->snapshots()->create($data);

        // load all params
        $snapshot->refresh();

        return Response::json($snapshot, 201);
    }

    public function update(Request $request, Snapshot $snapshot)
    {
        $data = $request->validate(Snapshot::validator());

        $snapshot->update($data);

        if ($snapshot->isStopped()) {
            $snapshot->retry();
        }
        else {
            $snapshot->download();
        }

        return Response::json($snapshot, 200);
    }

    public function destroy(Snapshot $snapshot)
    {
        $snapshot->delete();

        return Response::json([], 204);
    }

    public function retry(Request $request, Snapshot $snapshot)
    {
        $snapshot->retry();

        return Response::json([], 202);
    }

    public function stop(Request $request, Snapshot $snapshot)
    {
        $snapshot->stop();

        return Response::json([], 202);
    }

    public function refresh(Snapshot $snapshot)
    {
        $snapshot->download();

        return Response::json([], 202);
    }

    public function query(Request $request, Snapshot $snapshot)
    {
        if (!$snapshot->isCompleted()) {
            return Response::json(['error' => 'Snapshot is not available until it\'s completed.'], 423);
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

        // decide column type
        $fields = [];
        foreach ($snapshot->variables as $variable) {
            $fields[$variable->name] = $variable->isNumeric() ? 'double' : 'text';
        }

        $proxy = new QueryProxy($fields);

        foreach ($dump as $line) {
            $proxy->insert($line);
        }

        try {
            $results = $proxy->query($request->input('query'));
        } catch (\InvalidArgumentException $e) {
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
