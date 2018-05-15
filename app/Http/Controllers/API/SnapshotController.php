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

        $fields = collect();
        $tmpTables = collect();
        
        foreach ($snapshot->variables as $key => $variable) {
            $counter = 'lines' . $key;

            $fields[] = $variable->name;

            $tmpTables[] = '(SELECT
                @' . $counter .':=@' . $counter .'+1 AS `row`,
                `value` AS `' . $variable->name . '`
                FROM variable_values, (SELECT @' . $counter .':=0) AS ' . $counter .'
                WHERE variable_id = ' . (int) $variable->id . ' ORDER BY id) as `' . $variable->name . '`';
        }

        $where = $fields->crossJoin($fields)->map(function ($item) {
            return $item[0] . '.row = ' . $item[1] . '.row';
        });

        $limit = 1000;
        $offset = 0;

        $proxy = new QueryProxy($snapshot->variables->pluck('name'));

        do {
            $query = 'SELECT ' . $fields->implode(',') .
                ' FROM ' . $tmpTables->implode(',') .
                ' WHERE ' . $where->implode(' AND ') .
                ' LIMIT ' . $limit . ' OFFSET ' . $offset;

            $dump = DB::select($query);

            foreach ($dump as $line) {
                $proxy->insert($line);
            }

            $offset += $limit;

        } while (count($dump) === $limit);


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
