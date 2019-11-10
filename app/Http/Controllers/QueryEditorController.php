<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Snapshot;

class QueryEditorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Snapshot $snapshot)
    {
        $user = Auth::user();

        $snapshot = $snapshot->exists ? $snapshot : $user->snapshots()->latest()->first();

        return view('queries.editor', compact('user', 'snapshot'));
    }
}
