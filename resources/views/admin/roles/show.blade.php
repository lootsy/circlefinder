@extends('admin.layouts.app')

@section('title', $item)

@section('content')
    

    <p>Name: {{ $item->name }}</p>
    <p>Title: {{ $item->title }}</p>

    @if(count($item->users))
        <h2>Users</h2>

        <ul>
        @foreach($item->users as $user)
            <li>{{ $user->name }} ({{ $user->email }})</li>
        @endforeach
        </ul>
    @endif

    @include('admin.inc.res-action', ['item' => $item, 'route_prefix' => 'admin.roles.'])
    
@endsection