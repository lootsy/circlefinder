@extends('layouts.app')

@section('title', $item)

@section('content')

    <h1>@yield('title')</h1>
    
    <p>Title: {{ $item->title }}</p>
    <p>Completed: {{ $item->completed ? 'Yes': 'No' }}</p>
    <p>Limit: {{ $item->limit }}</p>
    <p>Type: {{ $item->type }}</p>
    <p>Begin: {{ $item->begin }}</p>
    <p>Owner: {{ $item->user->name }}</p>
    <p>Members: {{ count($item->memberships) }}</p>

    @if($item->full())
        <p>Circle is full!</p>
    @endif

    @if($item->completed)
        <p>Circle is completed!</p>
    @endif

    <h3>My membership</h3>


    @if($item->joined($user))
        <p>Type: {{ $membership->type }}</p>
        <p>Begin: {{ $membership->begin }}</p>

        <p><a href="{{ route('circles.membership.edit', ['uuid' => $item->uuid]) }}" class="btn btn-primary">Edit membership</a></p>

        {!! Form::open(['route' => ['circles.leave', 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
            {{ Form::submit('Leave circle', ['class' => 'btn btn-danger confirm']) }}
        {!! Form::close() !!}
    @else
        <p>You are not a member of {{ $item }}</p>
        @if($item->joinable($user))
        {!! Form::open(['route' => ['circles.join', 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
            {{ Form::submit('Join circle', ['class' => 'btn btn-success']) }}
        {!! Form::close() !!}
        @else
        <p>You can not join this circle.</p>
        @endif
    @endif

    <div class="border p-2 mt-4 mb-4">
        <a href="{{ route('circles.index') }}" class="btn btn-secondary">Back</a>

        @can('update', $item)
            <a href="{{ route('circles.edit', ['uuid' => $item->uuid]) }}" class="btn btn-primary">Edit circle</a>

            {!! Form::open(['route' => ['circles.'.($item->completed ? 'uncomplete' : 'complete'), 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
                {{ Form::submit($item->completed ? 'Uncomplete' : 'Complete', ['class' => 'btn btn-primary confirm']) }}
            {!! Form::close() !!}
        @endcan
    </div>

    @if(count($item->memberships))
        <h2>Memberships</h2>

        <ul>
        @foreach($item->memberships as $membership)
            <li>{{ $membership->user->name }}</li>
        @endforeach
        </ul>
    @endif

@endsection