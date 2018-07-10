@extends('layouts.app')

@section('title', 'Change my profile')

@section('content')

<h1>@yield('title')</h1>

<div class="card mt-3">
        <h5 class="card-header">{{ $item->name }}</h5>
        <div class="card-body">
        
            {!! Form::model($item, ['route' => ['profile.update'], 'method' => 'put']) !!}

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        {{ Form::label('name', 'Name', ['class' => 'required']) }}
                        {{ Form::text('name', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('email', 'E-Mail', ['class' => 'required']) }}
                        {{ Form::email('email', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('timezone', 'Timezone', ['class' => 'required']) }}
                        {!! Timezonelist::create('timezone', old('timezone', $item->timezone), ['class'=>'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {{ Form::label('about', 'About') }}
                        {{ Form::textarea('about', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        {{ Form::label('twitter_profile', 'Twitter Username') }}
                        {{ Form::text('twitter_profile', null, ['class' => 'form-control', 'placeholder' => '@@your_username']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('facebook_profile', 'Facebook Account') }}
                        {{ Form::text('facebook_profile', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('xing_profile', 'Xing URL') }}
                        {{ Form::text('xing_profile', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('linkedin_profile', 'LinkedIn URL') }}
                        {{ Form::text('linkedin_profile', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>

            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
            <a href="{{ route('profile.index') }}" class="btn btn-light">Cancel</a>

            {!! Form::close() !!}
        </div>
</div>
@endsection