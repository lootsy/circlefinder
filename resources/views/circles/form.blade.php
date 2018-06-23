@if(isset($item))
    {!! Form::model($item, ['route' => ['circles.update', 'uuid' => $item->uuid], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'circles.store']) !!}
@endif

    <div class="form-group">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', array_combine(config('circle.defaults.types'), config('circle.defaults.types')), null, ['class' => 'form-control']) }}
    </div>

    {{ $item->begin }}

    <div class="form-group">
        {{ Form::label('begin', 'Begin') }}
        {{ Form::date('begin', \Carbon\Carbon::now(), ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', null, ['class' => 'form-control']) }}
    </div>

    @if(isset($item))
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    @else
        {{ Form::submit('Create', ['class' => 'btn btn-success']) }}
    @endif
    
    @if(isset($item))
    <a href="{{ route('circles.show', ['uuid' => $item->uuid]) }}" class="btn btn-light">Cancel</a>
    @else
    <a href="{{ route('circles.index') }}" class="btn btn-light">Cancel</a>
    @endif
{!! Form::close() !!}