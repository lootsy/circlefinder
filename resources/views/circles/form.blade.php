@if(isset($item))
    {!! Form::model($item, ['route' => ['circles.update', 'uuid' => $item->uuid], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'circles.store']) !!}
@endif

    <div class="form-group">
        {{ Form::label('type', 'Type') }}
        {{ Form::select('type', list_of_types(), null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('begin', 'Begin') }}
        {{ Form::date('begin', isset($item) ? $item->begin->format('Y-m-d') : today(), ['class' => 'form-control']) }}
    </div>

    @include('inc.form-languages')

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
    {!! $item->link('Cancel', 'btn btn-light') !!}
    @else
    <a href="{{ route('circles.index') }}" class="btn btn-light">Cancel</a>
    @endif
{!! Form::close() !!}