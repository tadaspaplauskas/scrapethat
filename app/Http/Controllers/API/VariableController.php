<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Snapshot;
use App\Models\Variable;
use DB;
use Auth;
use Response;

class VariableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Snapshot $snapshot)
    {
        $variables = $snapshot->variables()->orderBy('created_at', 'desc')->get();

        return Response::json($variables);
    }

    public function show(Variable $variable)
    {
        return Response::json($variable);
    }

    public function store(Snapshot $snapshot, Request $request)
    {
        if (!$snapshot->isCompleted()) {
            return Response::json(['error' => 'Snapshot is not available until it\'s completed.'], 423);
        }

        $data = $request->validate(Variable::validator($snapshot));

        $data['name'] = snake_case($data['name']);

        $variable = $snapshot->variables()->create($data);

        // load all params
        $variable->refresh();

        return Response::json($variable, 201);
    }

    public function update(Request $request, Variable $variable)
    {
        $snapshot = $variable->snapshot;

        if (!$snapshot->isCompleted()) {
            return Response::json(['error' => 'Snapshot is not available until it\'s completed.'], 423);
        }

        $data = $request->validate(Variable::validator($snapshot, $variable));

        $data['name'] = snake_case($data['name']);

        $variable->update($data);

        $variable->process();

        return Response::json($variable, 200);
    }

    public function destroy(Variable $variable)
    {
        $variable->delete();

        return Response::json([], 204);
    }
}
