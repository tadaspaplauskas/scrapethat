<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Snapshot;
use App\Models\Variable;

class VariableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Snapshot $snapshot)
    {
        if (!$snapshot->isCompleted()) {
            return redirect()->back()->withInput()->with('message', 'Snapshot is still in progress, please wait until it is completed');
        }

        $data = $request->validate(Variable::validator($snapshot));

        $data['name'] = snake_case($data['name']);

        $variable = $snapshot->variables()->create($data);

        $variable->process();

        return redirect()->back()->with('message', $variable->name . ' was added.');
    }

    public function edit(Request $request, Variable $variable)
    {
        return view('variables.edit', compact('variable'));
    }

    public function update(Request $request, Variable $variable)
    {
        $snapshot = $variable->snapshot;

        $data = $request->validate(Variable::validator($snapshot));

        $variable->update($data);

        $variable->process();

        return redirect()->back()->with('message', $variable->name . ' was updated.');
    }

    public function confirmDelete(Variable $variable)
    {
        return view('variables.delete', compact('variable'));
    }

    public function destroy(Snapshot $snapshot, Variable $variable)
    {
        $variable->delete();

        return redirect()->back()->with('message', $variable->name . ' was deleted.');
    }
}
