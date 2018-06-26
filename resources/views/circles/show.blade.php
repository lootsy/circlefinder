@extends('layouts.app')

@section('title', $item)

@section('content')

    <h1>{{ $item->goodTitle() }}</h1>

    @if($item->completed)
        <div class="alert alert-warning">Circle is completed</div>
    @else
        @if($item->full())
            <div class="alert alert-warning">Circle is full</div>
        @endif
    @endif

    <div class="mb-3">
        @if($item->joined($user))
            <a href="{{ route('circles.membership.edit', ['uuid' => $item->uuid]) }}" class="btn btn-secondary">Edit my membership</a>

            {!! Form::open(['route' => ['circles.leave', 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
                {{ Form::submit('Leave circle', ['class' => 'btn btn-danger confirm']) }}
            {!! Form::close() !!}
        @else
            @if($item->joinable($user))
                {!! Form::open(['route' => ['circles.join', 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
                    {{ Form::submit('Join circle', ['class' => 'btn btn-success']) }}
                {!! Form::close() !!}
            @endif
        @endif

        @can('update', $item)
            <a href="{{ route('circles.edit', ['uuid' => $item->uuid]) }}" class="btn btn-secondary">Edit circle</a>

            {!! Form::open(['route' => ['circles.'.($item->completed ? 'uncomplete' : 'complete'), 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
                {{ Form::submit($item->completed ? 'Uncomplete' : 'Complete', ['class' => 'btn btn-primary confirm']) }}
            {!! Form::close() !!}

            @if($item->deletable())
            {!! Form::open(['route' => ['circles.destroy', 'uuid' => $item->uuid], 'class' => 'd-inline-block']) !!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete circle', ['class' => 'btn btn-danger confirm']) }}
            {!! Form::close() !!}
            @endif
        @endcan
    </div>

    <div class="card">
        <h5 class="card-header">Members</h5>
    
        <div class="card-body">
            

            @if(count($item->memberships))        
                <table class="table table-striped">
                    <tr>
                        <th>Name</th>
                        <th>Type (virtual/f2f)</th>
                        <th>Begin</th>
                        <th>Language</th>
                    </tr>
                    
                    @foreach($item->memberships as $memb)
                    <tr>
                        <td class="align-middle">{!! $memb->user->link() !!}</td>
                        <td class="align-middle">{{ $memb->type }}</td>
                        <td class="align-middle">{{ $memb->begin }}</td>
                        <td class="align-middle">{{ $memb->languages->implode('title', ', ') }}</td>
                    </tr>
                    @endforeach
                </table>
            @else
                <p>Currenly there are no members in the circle</p>
            @endif
        </div>
    </div>

    <div class="card mt-4">
        <h5 class="card-header">Circle data</h5>

        <div class="card-body">
            <div class="row">
                <div class="col-lg col-12">
                    <p>Completed: {{ $item->completed ? 'Yes': 'No' }}</p>
                    <p>Limit: {{ $item->limit }}</p>
                    <p>Type: {{ $item->type }}</p>
                    <p>Begin: {{ $item->begin }}</p>
                    <p>Languages: {{ $item->languages->implode('title', ', ') }}</p>
                    <p>Owner: {{ $item->user->name }}</p>
                </div>
        
                <div class="col-lg col-12">                   

                </div>
            </div>
        </div>
    </div>

@endsection