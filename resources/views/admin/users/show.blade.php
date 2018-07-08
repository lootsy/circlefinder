@extends('admin.layouts.app')

@section('title', $item)

@section('content')
    
    <h2>User data</h2>
    <p>Name: {{ $item->name }}</p>
    <p>E-Mail: {{ $item->email }}</p>
    <p>Timezone: {{ $item->timezone }}</p>
    <p>UTC Time offset: {{ $item->time_offset }}</p>
    <p>UUID: <a href="{{ route('profile.show', ['uuid' => $item->uuid]) }}">{{  $item->uuid }}</a></p>

    @if(count($item->roles))
        <h2>Roles</h2>

        <ul>
        @foreach($item->roles as $role)
            <li>{{ $role->title }} ({{ $role->name }})</li>
        @endforeach
        </ul>
    @endif

    @include('admin.inc.res-action', ['item' => $item, 'route_prefix' => 'admin.users.'])
@endsection