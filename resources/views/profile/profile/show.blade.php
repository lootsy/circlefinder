@extends('layouts.app')

@section('title', $item->name)

@section('content')
    
    

    <div class="card mt-3">
        <h5 class="card-header">{{ $item->name }}</h5>
        
        @if(auth()->user()->id == $item->id)
            <div class="card-body">    
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit profile</a>
                <a href="{{ route('profile.avatar.edit') }}" class="btn btn-primary">Change avatar</a>
                @if($item->no_password == false)
                <a href="{{ route('profile.password.edit') }}" class="btn btn-primary">Change password</a>
                @endif
            </div>
        @endif

        <div class="card-body row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            {!! user_avatar($item) !!}
                        </div>

                        @if(count($profiles))
                        <ul class="profiles">
                            <h5>Social profiles</h5>
                            @foreach($profiles as $profile => $link)
                            <li><a href="{{ $link }}"><i class="fa fa-{{ $profile }}-square"></i></a></li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-8">
                @if($item->about)
                <div class="mb-4">
                    <h5 class="card-title">About</h5>
                    <p class="card-text">{{ $item->about }}</p>
                </div>
                @endif

                <h5 class="card-title">Owned circles</h5>
                
                @if(count($item->circles))
                    <ul>
                    @foreach($item->circles as $circle)
                        <li><a href="{{ route('circles.show', ['uuid' => $circle->uuid]) }}">{{ $circle }}</a></li>
                    @endforeach
                    </ul>
                @else
                    <p>No circles</p>
                @endif

                <h5 class="card-title">Member of circles</h5>
                
                @if(count($item->memberships))
                    <ul>
                    @foreach($item->memberships as $membership)
                        <li><a href="{{ route('circles.show', ['uuid' => $membership->circle->uuid]) }}">{{ $membership->circle }}</a></li>
                    @endforeach
                    </ul>
                @else
                    <p>No circles</p>
                @endif
            </div>
        </div>
    </div>

@endsection