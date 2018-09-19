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

    public function index()
    {
        $snapshots = Auth::user()->snapshots()->orderBy('created_at', 'desc')->get();

        return view('snapshots.index', compact('snapshots'));
    }

    public function create()
    {
        return view('snapshots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate(Snapshot::validator());

        $snapshot = Auth::user()->snapshots()->create($data);

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was created successfully. Please wait while we crawl the pages.');
    }

    public function show(Snapshot $snapshot, Request $request)
    {
        $user = Auth::user();

        $variables = $snapshot->variables;

        return view('snapshots.show', compact('snapshot', 'variables', 'user'));
    }

    public function edit(Request $request, Snapshot $snapshot)
    {
        $notificationId = $request->input('notification_id');

        return view('snapshots.edit', compact('snapshot', 'notificationId'));
    }

    public function update(Request $request, Snapshot $snapshot)
    {
        $data = $request->validate(Snapshot::validator());

        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            $notification = Auth::user()->unreadNotifications()->find($notificationId);

            if ($notification) {
                $notification->markAsRead();
            }
        }

        $snapshot->update($data);

        $snapshot->retry();

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was updated successfully. Please wait while we crawl the pages.');
    }

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

        $snapshot->retry();

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
