<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class QueryEditorController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $snapshot = $request->has('snapshot') ?
            $user->snapshots()->whereKey($request->get('snapshot'))->first()
            :
            $user->snapshots()->latest()->first();

        return view('query_editor', compact('user', 'snapshot'));
    }
}
