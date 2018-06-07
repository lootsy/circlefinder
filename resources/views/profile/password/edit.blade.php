@extends('layouts.app')

@section('title', 'Change my password')

@section('content')

<h1>@yield('title')</h1>

<div class="card mt-3">
        
        <div class="card-body">
        
            {!! Form::open(['route' => ['profile.password.update'], 'method' => 'put']) !!}

            <div class="form-group">
                {{ Form::label('current_password', 'Current Password') }}
                {{ Form::password('current_password', ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('password', 'New Password') }}
                {{ Form::password('password', ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('password_confirmation', 'Confirm new Password') }}
                {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
            </div>

            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
            <a href="{{ route('profile.index') }}" class="btn btn-light">Cancel</a>

            {!! Form::close() !!}
        </div>
</div>
@endsection