{!! Form::model($item, ['route' => ['circles.membership.update', 'uuid' => $item->circle->uuid], 'method' => 'put']) !!}

    <div class="row">
        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('type', 'Type') }}
                {{ Form::select('type', array_combine(config('circle.defaults.types'), config('circle.defaults.types')), null, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="col-lg col-12">
            <div class="form-group">
                {{ Form::label('begin', 'Begin') }}
                {{ Form::date('begin', isset($item) ? null : today(), ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    @include('inc.form-languages')

    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
    
    <a href="{{ route('circles.show', ['uuid' => $item->circle->uuid]) }}" class="btn btn-light">Cancel</a>

{!! Form::close() !!}