@extends('layouts.app')

@section('title', $snapshot->name . ' variables')

@section('content')

    <p>
        Use CSS selectors to get data out of the pages.
    </p>

    <p>
        <a href="{{ route('variables.create', $snapshot) }}">Make a new one.</a>
    </p>

    @if (!$variables->isEmpty())

        <table class="full-width">
            <tr>
                <th>Name</th>
                <th>Selector</th>
                <th>Created at</th>
                <th>Delete</th>
            </tr>

            @foreach ($variables as $variable)
                <tr>
                    <td>
                        {{ $variable->name }}
                    </td>
                    <td>
                        {{ $variable->selector }}
                    </td>
                    <td>
                        {{ $variable->created_at->diffForHumans() }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('variables.destroy', [$snapshot, $variable]) }}" class="m0">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button>Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>

    @endif

@endsection
