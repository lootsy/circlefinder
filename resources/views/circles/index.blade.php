@extends('layouts.app')

@section('title', 'Circles')

@section('content')

    <h1>@yield('title')</h1>

    <a href="{{ route('circles.create') }}" class="btn btn-success mb-4">New</a>
        
    @if(count($items) > 0)

    @include('inc.pagination')

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
            <td class="align-middle">{!! $item->link($item->id) !!}</a></td>
            <td class="align-middle">{!! $item->link($item->title) !!}</td>
            <td class="align-middle">{{ $item->completed ? 'Yes': 'No' }}</td>
            <td class="align-middle">{{ $item->memberships()->count() }} / {{ $item->limit }}</td>
            <td class="align-middle">{{ $item->type }}</td>
            <td class="align-middle">{!! $item->user->link() !!}</td>
        </tr>
        @endforeach
    </table>

    @include('inc.pagination')

    @else
        <p>No circles were found</p>
    @endif

@endsection