<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Query;
use App\Http\Requests\QueryRequest;

class QueryController extends Controller
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
        $queries = Auth::user()->queries;

        return view('queries.index', [
            'queries' => $queries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('queries.create', [
            'query' => $request->input('query'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QueryRequest $request)
    {
        Auth::user()->queries()->create($request->all());

        return redirect()->action('QueryController@index')
            ->withMessage('Query was deleted');
    }

    public function destroy(Query $query)
    {
        $query->delete();

        return redirect()->action('QueryController@index')
            ->withMessage('Query was deleted.');
    }

    public function confirmDelete(Query $query)
    {
        return view('queries.delete', compact('query'));
    }
}
