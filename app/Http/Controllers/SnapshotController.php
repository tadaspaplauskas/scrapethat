<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Snapshot;

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

        $data['crawled'] = 0;
        $data['total'] = abs($data['to'] - $data['from'] + 1);

        auth()->user()->snapshots()->create($data);

        return redirect()->action('SnapshotController@index')
            ->with('message', $request->name . ' saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Snapshot $snapshot)
    {
        return view('snapshots.show', compact('snapshot'));
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
