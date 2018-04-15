<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

use App\Snapshot;
use App\Variable;
use App\Jobs\ProcessVariable;

class VariableController extends Controller
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
    public function index(Snapshot $snapshot)
    {
        $variables = $snapshot->variables;

        return view('variables.index', compact('snapshot', 'variables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Snapshot $snapshot)
    {
        // TODO validate, selector should be unique
        $request->validate([
            'name' => 'required|alpha_num',
            'selector' => 'required',
        ]);

        if ($snapshot->isCompleted()) {
            return redirect()->back()->withInput()->withErrors();
        }

        if ($snapshot->isCompleted()) {
            return redirect()->back()->withInput()->withErrors();
        }        

        $data = $request->all();

        $data['name'] = snake_case($data['name']);

        $variable = Variable::create($data);

        ProcessVariable::dispatch($variable);

        return redirect()->back()->withInput();
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
    public function update(Request $request, Variable $variable)
    {
        $variable->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variable $variable)
    {
       $variable->delete();

        return redirect()->action('SnapshotController@show', $variable->snapshot)
            ->with('message', $variable->name . ' was deleted.');
    }
}
