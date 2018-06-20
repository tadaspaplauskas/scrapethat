<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Validation\Rule;

use App\Models\Snapshot;
use App\Models\Variable;
use App\Jobs\ProcessVariable;
use App\Rules\ValidCssSelector;

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

        $uniqueRule = Rule::unique('variables')->where(function ($q) use ($snapshot) {
            return $q->where('snapshot_id', $snapshot->id);
        });

        $data = $request->validate([
            'name' => ['alpha_num', $uniqueRule],
            'selector' => ['required', new ValidCssSelector, $uniqueRule],
        ]);

        if (!$data['name']) {
            $data['name'] = $data['selector'];
        }

        $data['name'] = snake_case($data['name']);

        $variable = $snapshot->variables()->create($data);

        ProcessVariable::dispatch($variable);

        return redirect()->back()->with('message', $variable->name . ' was added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Variable $variable, Request $request)
    {
        $numbers = $variable->values->map(function ($item) {
            return floatval(preg_replace('/\s*/m', '', $item));
        });

        return view('filters.show', compact('filter', 'numbers'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Snapshot $snapshot, Variable $variable)
    {
        $variable->delete();

        return redirect()->back()->with('message', $variable->name . ' was deleted.');
    }
}
