{!! Form::model($item, ['route' => ['circles.membership.update', 'uuid' => $item->circle->uuid], 'method' => 'put']) !!}

    <div class="row">
        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('begin', 'Begin', ['class' => 'required']) }}
                {{ Form::date('begin', isset($item) ? $item->begin->format('Y-m-d') : today(), ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('type', 'Type', ['class' => 'required']) }}
                {{ Form::select('type', list_of_types(), null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6">
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
                    <td class="time">{{ $time }}:00</td>
                    @foreach($timeTable->getDayList() as $day)
                    <td class="check {{ $timeTable->checksAt($day, $time) ? 'checks' : 'no-checks' }} {{ (is_array($timeSlot->$day) && in_array($time, $timeSlot->$day)) ? ' checked' : '' }}">
                        <small>{{ $timeTable->checksAt($day, $time) }}</small>
                        {{ Form::checkbox($day.'[]', $time, is_array($timeSlot->$day) && in_array($time, $timeSlot->$day)) }}
                    </td>
                    @endforeach
                </tr>
            @endforeach
            </table>
        </div>

        <div class="col-12 col-lg-6">
            @include('inc.form-languages')
        </div>
    </div>

    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
    
    <a href="{{ route('circles.show', ['uuid' => $item->circle->uuid]) }}" class="btn btn-light">Cancel</a>

{!! Form::close() !!}