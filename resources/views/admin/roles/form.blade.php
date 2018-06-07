@if(isset($item))
    {!! Form::model($item, ['route' => ['admin.roles.update', 'id' => $item->id], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'admin.roles.store']) !!}
@endif

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', null, ['class' => 'form-control']) }}
    </div>
    
    @if(isset($item))
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    @else
        {{ Form::submit('Create', ['class' => 'btn btn-success']) }}
    @endif

@if(isset($item) && $item->trashed())
    <a href="{{ route('admin.roles.trash') }}" class="btn btn-light">Cancel</a>
@else
    <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Cancel</a>
@endif

{!! Form::close() !!}