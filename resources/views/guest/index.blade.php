@extends('layouts.app')

@section('title', 'Welcome to the CircleFinder!')

@section('content')

    <h1>@yield('title')</h1>
        
    @if(count($items) > 0)

    <h2>Our newest circles</h2>

    <table class="table table-striped table-bordered">
        <tr>
            <th>Name</th>
            <th>Begin</th>
            <th>Completed</th>
            <th>Members</th>
            <th>Type (virtual/f2f)</th>
        </tr>
        
        @foreach($items as $item)
        <tr>
            <td class="align-middle">{!! $item->link('Circle '.$item->id) !!}</a></td>
            <td class="align-middle">{{ $item->begin }}</td>
            <td class="align-middle">{{ $item->completed ? 'Yes': 'No' }}</td>
            <td class="align-middle">{{ $item->memberships()->count() }} / {{ $item->limit }}</td>
            <td class="align-middle">{{ $item->type }}</td>
        </tr>
        @endforeach
    </table>
    
    @endif

@endsection