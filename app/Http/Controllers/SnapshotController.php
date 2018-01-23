<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Snapshot;
use App\Jobs\DownloadPage;

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
        $data = $request->all();

        $data['total'] = abs($data['to'] - $data['from'] + 1);

        $snapshot = auth()->user()->snapshots()->create($data);

        DownloadPage::dispatch($snapshot);

        return redirect()->action('SnapshotController@index')
            ->with('message', $request->name . ' created successfully. Please wait while we crawl the pages.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Snapshot $snapshot, Request $request)
    {
        $filters = $snapshot->filters;
        
        $dataset = collect();

        foreach ($filters as $filter) {
            foreach ($filter->values as $i => $value) {
                if (!isset($dataset[$i])) {
                    $dataset[$i] = collect();
                }

                $dataset[$i][$filter->name] = floatval(preg_replace('/\s*/m', '', $value));
            }
        }

        return view('snapshots.show', compact('snapshot', 'filters', 'dataset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function restore($id)
    {
        $snapshot = Snapshot::withTrashed()->find($id);

        $snapshot->restore();

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was restored.');
    }
}
