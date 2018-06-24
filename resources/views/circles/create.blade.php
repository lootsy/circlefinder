@extends('layouts.app')

@section('title', 'New Circle')

@section('content')

<h1>@yield('title')</h1>

<div class="card mt-3">
        <h5 class="card-header">Create a new circle</h5>
        <div class="card-body">
            @include('circles.form')
        </div>
</div>

@endsection