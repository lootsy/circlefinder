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
                <table class="table table-striped table-bordered">
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

                    @for($i = 0; $i < $item->limit - count($item->memberships); $i++)
                        <tr>
                            <td class="align-middle">&nbsp;</td>
                            <td class="align-middle">&nbsp;</td>
                            <td class="align-middle">&nbsp;</td>
                            <td class="align-middle">&nbsp;</td>
                        </tr>
                    @endfor
                </table>
            @else
                <p>Currenly there are no members in the circle</p>
            @endif
        </div>
    </div>

    @if($timeTable)
    <div class="card mt-4">
        <h5 class="card-header">Time schedule</h5>
        
        <div class="card-body">
            <table class="table table-sm table-striped table-bordered time-schedule">
                <tr>
                    <th>Time</th>
                    <th>User</th>
                    @foreach($timeTable->getDayList(true) as $day)
                    <th>{{ $day }}</th>
                    @endforeach
                </tr>
            @foreach($timeTable->getTimeList() as $time)
                @foreach($timeTable->memberships() as $membership)
                    @if($timeTable->atTime($time))
                    <tr>
                        @if($loop->first)
                        <td rowspan="{{ count($timeTable->memberships()) }}">{{ $time }}:00</td>
                        @endif

                        <td><small>{{ $membership->user->name }}</small></td>
                                                
                        @foreach($timeTable->getDayList() as $day)
                            @if(is_array($membership->timeSlot->$day) && in_array($time, $membership->timeSlot->$day))
                            <td class="time-ok">✅</td>
                            @else
                            <td>✖️</td>
                            @endif
                        @endforeach
                    </tr>
                    @endif
                @endforeach
            @endforeach
            </table>
        </div>
    </div>
    @endif

    <div class="card mt-4">
        <h5 class="card-header">Circle data</h5>

        <div class="card-body">
            <div class="row">
                <div class="col-lg col-12">
                    <p>Type: {{ $item->type }}</p>
                    <p>Begin: {{ $item->begin }}</p>
                </div>
        
                <div class="col-lg col-12">                   
                    <p>Languages: {{ $item->languages->implode('title', ', ') }}</p>
                    <p>Owner: {!! $item->user->link() !!}</p>
                </div>
            </div>
        </div>
    </div>

@endsection