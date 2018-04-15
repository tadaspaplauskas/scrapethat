@extends('layouts.app')

@section('title', $snapshot->name . ' variables')

@section('content')

    <p>
        Select data out of snapshots using CSS selectors.
    </p>

    <p>
        <a href="{{ route('variables.create', $snapshot) }}">Make a new one</a>.
    </p>

    @forelse ($variables as $variable)
        <article class="m0 center column one-half">
            <h5>
                {{ $variable->name }}
            </h5>
            <p>
                <small>
                    {{ $variable->selector }}

                    <form method="POST" action="{{ route('variables.destroy', [$snapshot, $variable]) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button>Delete</button>
                    </form>
                </small>
            </p>
        </article>
    @empty
        <p>
            No snapshots yet.
        </p>
    @endforelse

@endsection
