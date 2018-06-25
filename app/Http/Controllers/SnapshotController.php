<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Snapshot;
use App\Jobs\DownloadPage;
use App\Services\QueryProxy;
use DB;
use Auth;

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
        $snapshots = Auth::user()->snapshots()->orderBy('created_at', 'desc')->get();

        return view('snapshots.index', compact('snapshots'));
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
        $data = $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ]);

        $snapshot = Auth::user()->snapshots()->create($data);

        DownloadPage::dispatch($snapshot);

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was created successfully. Please wait while we crawl the pages.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Snapshot $snapshot, Request $request)
    {
        $user = Auth::user();

        $variables = $snapshot->variables;

        return view('snapshots.show', compact('snapshot', 'variables', 'user'));
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
        $data = $request->validate([
            'name' => 'required',
            'url' => 'required|url',
            'from' => 'required|integer',
            'to' => 'required|integer',
        ]);

        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = Auth::user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        // reset last page
        $snapshot->pages()->latest()->first()->delete();

        $snapshot->current--;

        $snapshot->save();

        $snapshot->update($data);

        DownloadPage::dispatch($snapshot);

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was updated successfully. Please wait while we crawl the pages.');
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
            $notification = Auth::user()->unreadNotifications()->find($notificationId);

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
            $notification = Auth::user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was stopped.');
    }
}
