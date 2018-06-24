@extends('layouts.app')

@section('title', 'Edit ' . $item)

@section('content')

<h1>@yield('title')</h1>

<div class="card mt-3">
        <h5 class="card-header">{{ $item }}</h5>
        <div class="card-body">
            @include('membership.form')
        </div>
</div>

@endsection