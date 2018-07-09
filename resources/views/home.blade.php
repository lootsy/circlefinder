@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h1>Hello, {{ $user->name }}</h1>
                    <p>Welcome to your Dashboard!</p>

                    <p>You can edit your profile <a href="{{ route('profile.index') }}">here</a></p>

                    <h2>My circles</h2>
                    @if(count($items))
                        <ul>
                        @foreach($items as $circle)
                            <li>{!! $circle->link() !!}</li>
                        @endforeach
                        </ul>
                    @else
                        <p>You have no circles. You can <a href="{{ route('circles.create') }}">create one here</a>!</p>
                    @endif

                    <h2>Circles I'm in</h2>
                    @if(count($memberships))
                        <ul>
                        @foreach($memberships as $membership)
                            <li>{!! $membership->circle->link() !!}</li>
                        @endforeach
                        </ul>
                    @else
                        <p>Looks like you are not in a circle. You can <a href="{{ route('circles.index') }}">find one here</a>!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
