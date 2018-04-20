<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Snapshot;
use App\Jobs\DownloadPage;
use App\Services\QueryProxy;
use DB;

class SnapshotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('snapshots.index', [
            'snapshots' => auth()->user()->snapshots()->orderBy('id', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('snapshots.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ]);

        $snapshot = auth()->user()->snapshots()->create($request->all());

        DownloadPage::dispatch($snapshot);

        return redirect()->action('SnapshotController@index')
            ->with('message', $request->name . ' was created successfully. Please wait while we crawl the pages.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Snapshot $snapshot, Request $request)
    {
        $variables = $snapshot->variables;
        
        $dataset = collect();

        foreach ($variables as $variable) {
            foreach ($variable->values as $i => $value) {
                if (!isset($dataset[$i])) {
                    $dataset[$i] = collect();
                }

                $dataset[$i][$variable->name] = floatval(preg_replace('/\s*/m', '', $value));
            }
        }

        return view('snapshots.show', compact('snapshot', 'variables', 'dataset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Snapshot $snapshot)
    {
        $notificationId = $request->input('notification_id');

        return view('snapshots.edit', compact('snapshot', 'notificationId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Snapshot $snapshot)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ]);

        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = auth()->user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        // reset last page
        $snapshot->pages()->latest()->first()->delete();

        $snapshot->current--;

        $snapshot->save();

        $snapshot->update($request->all());

        DownloadPage::dispatch($snapshot);

        return redirect()->action('SnapshotController@index')
            ->with('message', $request->name . ' was updated successfully. Please wait while we crawl the pages.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Snapshot $snapshot)
    {
        $snapshot->delete();

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was deleted.
                <a href="' . route('snapshots.restore', $snapshot) . '" class="block">Undo</a>');
    }

    public function delete(Snapshot $snapshot)
    {
        return view('snapshots.delete', compact('snapshot'));
    }

    public function restore($id)
    {
        $snapshot = Snapshot::withTrashed()->find($id);

        $snapshot->restore();

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was restored.');
    }

    public function retry(Request $request, Snapshot $snapshot)
    {
        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = auth()->user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        $snapshot->pages()->latest()->first()->delete();

        $snapshot->current--;

        $snapshot->save();

        DownloadPage::dispatch($snapshot);
        
        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' is queued again.');
    }

    public function stop(Request $request, Snapshot $snapshot)
    {
        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = auth()->user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was stopped.');
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

        $variables = DB::select('SELECT ' . $fields->implode(',') .
            ' FROM ' . $tmpTables->implode(',') .
            ' WHERE ' . $where->implode(' AND '));

        dd($variables);

        // $proxy = new QueryProxy();

        // try {

        //     $results = $proxy->query($request->input('q'));

        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Check query syntax.'], 422);
        // }

        // SELECT * FROM 
        //     (SELECT author,score
        //     FROM
        //     (SELECT @line1:=@line1+1 AS `row`, `value` as `author` FROM variable_values, (SELECT @line1:=0) AS var1 WHERE variable_id = 2 ORDER BY id) as `author`,
        //     (SELECT @line2:=@line2+1 AS `row`, `value` as `score` FROM variable_values, (SELECT @line2:=0) AS var2 WHERE variable_id = 1 ORDER BY id) as `score`
        //     WHERE
        //     author.row = score.row) compiled;
        // create temporary table for snapshot
        // validate the query with EXPLAIN
        // finally run passed query on that temp table
        // return results as json dump
    }
}
