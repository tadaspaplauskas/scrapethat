<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Snapshot;
use App\Jobs\DownloadPage;
use Auth;

class SnapshotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $snapshots = Auth::user()->snapshots;

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

        $snapshot->download();

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was created successfully. Please wait while we crawl the pages.');
    }

    public function show(Snapshot $snapshot, Request $request)
    {
        $variables = $snapshot->variables;

        return view('snapshots.show', compact('snapshot', 'variables'));
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

        if ($snapshot->isStopped()) {
            $snapshot->retry();
        }

        return redirect()->action('SnapshotController@index')
            ->with('message', $data['name'] . ' was updated successfully.');
    }

    public function destroy(Snapshot $snapshot)
    {
        $snapshot->delete();

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' was deleted.');
    }

    public function confirmDelete(Snapshot $snapshot)
    {
        return view('snapshots.delete', compact('snapshot'));
    }

    public function confirmRefresh(Snapshot $snapshot)
    {
        return view('snapshots.refresh', compact('snapshot'));
    }

    public function refresh(Snapshot $snapshot)
    {
        if ($snapshot->isInProgress()) {
            return redirect()->action('SnapshotController@index')
                ->with('message', $snapshot->name . ' refresh is already in progress.');
        }

        $snapshot->download();

        return redirect()->action('SnapshotController@index')
            ->with('message', $snapshot->name . ' is queued.');
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
