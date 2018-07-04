@extends('admin.layouts.app')

@if(is_trash_route())
@section('title', 'Trash: Users')
@else
@section('title', 'Users')
@endif

@section('content')
    @include('admin.inc.index-nav', ['route_prefix' => 'admin.users.'])

    @if($items->count() > 0)

    @include('admin.inc.pagination')

    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <td>No passw.</td>
            <th>Roles</th>
            <th>Action</th>
        </tr>
        
        @foreach($items as $item)
        <tr class="item-{{ $item->id }}">
            <td class="align-middle">{{ $item->id }}</td>
            <td class="align-middle"><a href="{{ route('admin.users.show', ['id' => $item->id]) }}">{{ $item->name }}</a></td>
            <td class="align-middle">{{ $item->no_password ? 'Yes' : 'No' }}</td>
            <td class="align-middle">{{ $item->roles->implode('title', ', ') }}</td>
            <td>@include('admin.inc.res-action', ['item' => $item, 'route_prefix' => 'admin.users.'])</td>
        </tr>
        @endforeach
    </table>
    
    @include('admin.inc.pagination')

    @else
        <p>No users were found</p>
    @endif
@endsection