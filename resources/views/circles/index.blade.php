@extends('layouts.app')

@section('title', 'Circles')

@section('content')

    <h1>@yield('title')</h1>
        
    @if(count($items) > 0)

    @include('admin.inc.pagination')

    <table class="table table-striped">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Completed</th>
            <th>Members</th>
            <th>Type (virtual/f2f)</th>
            <th>Owner</th>
        </tr>
        
        @foreach($items as $item)
        <tr class="item-{{ $item->id }}">
            <td class="align-middle">{{ $item->id }}</td>
            <td class="align-middle"><a href="{{ route('circles.show', ['uuid' => $item->uuid]) }}">{{ $item->title }}</a></td>
            <td class="align-middle">{{ $item->completed ? 'Yes': 'No' }}</td>
            <td class="align-middle">{{ $item->memberships()->count() }} / {{ $item->limit }}</td>
            <td class="align-middle">{{ $item->type }}</td>
            <td class="align-middle"><a href="{{ route('profile.show', $item->user->uuid) }}">{{ $item->user->name }}</a></td>
        </tr>
        @endforeach
    </table>

    @include('admin.inc.pagination')

    @else
        <p>No circles were found</p>
    @endif

@endsection