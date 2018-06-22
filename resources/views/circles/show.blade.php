@extends('layouts.app')

@section('title', $item)

@section('content')

    <h1>@yield('title')</h1>
    
    <p>Title: {{ $item->title }}</p>
    <p>Completed: {{ $item->completed ? 'Yes': 'No' }}</p>
    <p>Limit: {{ $item->limit }}</p>
    <p>Type: {{ $item->type }}</p>
    <p>Members: {{ count($item->memberships) }}</p>

    @if($item->joinable($user))
        <a href="{{ route('circles.membership.create', ['uuid' => $item->uuid]) }}" class="btn btn-success">Join circle</a>
    @else
        @if($item->full())
            <p>Circle is full!</p>
        @endif
        @if($item->completed)
            <p>Circle is completed!</p>
        @endif
    @endif

    @if($item->joined($user))
        <a href="{{ route('circles.membership.destroy', ['uuid' => $item->uuid]) }}" class="btn btn-success">Leave circle</a>
    @endif

    @can('update', $item)
        <a href="{{ route('circles.edit', ['uuid' => $item->uuid]) }}" class="btn btn-primary">Edit circle</a>
    @endcan

    @if(count($item->memberships))
        <h2>Memberships</h2>

        <ul>
        @foreach($item->memeberships as $membership)
            <li>{{ $membership->user->name }}</li>
        @endforeach
        </ul>
    @endif

@endsection