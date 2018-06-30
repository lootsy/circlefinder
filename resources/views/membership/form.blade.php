{!! Form::model($item, ['route' => ['circles.membership.update', 'uuid' => $item->circle->uuid], 'method' => 'put']) !!}

    <div class="row">
        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('begin', 'Begin') }}
                {{ Form::date('begin', isset($item) ? null : today(), ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', array_combine(config('circle.defaults.types'), config('circle.defaults.types')), null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <h2>Time schedule</h2>
            <table class="table table-sm table-striped table-bordered time-schedule">
                <tr>
                    <th>Time</th>
                    @foreach($timeTable->getDayList(true) as $day)
                    <th>{{ $day }}</th>
                    @endforeach
                </tr>
            @foreach($timeTable->getTimeList() as $time)
                <tr>
                    <td>{{ $time }}</td>
                    @foreach($timeTable->getDayList() as $day)
                    <td>{{ Form::checkbox($day.'[]', $time) }}</td>
                    @endforeach
                </tr>
            @endforeach
            </table>
        </div>
        <div class="col-6">
            @include('inc.form-languages')
        </div>
    </div>

    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
    
    <a href="{{ route('circles.show', ['uuid' => $item->circle->uuid]) }}" class="btn btn-light">Cancel</a>

{!! Form::close() !!}