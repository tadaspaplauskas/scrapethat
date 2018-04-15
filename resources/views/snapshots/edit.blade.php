@extends('layouts.app')

@section('title', 'Edit ' . $snapshot->name)

@section('content')

<form method="POST" action="{{ route('snapshots.update', $snapshot) }}">
    
    {{ csrf_field() }}

    {{ method_field('PUT') }}

    <input type="hidden" name="notification_id" value="{{ $notificationId }}">

    @include('snapshots.form')
    
    <button type="submit" class="block">Save</button>

</form>
@endsection
