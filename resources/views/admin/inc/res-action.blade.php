<div class="res-action" role="group">
    <a href="{{ route($route_prefix.'edit', ['id' => $item->id]) }}" class="edit btn btn-secondary">Edit</a>

    @if($item->trashed())
        {!! Form::open(['route' => [$route_prefix.'restore', 'id' => $item->id], 'class' => 'd-inline-block']) !!}
            {{ Form::submit('Restore', ['class' => 'btn btn-success confirm']) }}
        {!! Form::close() !!}

        {!! Form::open(['route' => [$route_prefix.'forcedelete', 'id' => $item->id], 'class' => 'd-inline-block']) !!}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Delete', ['class' => 'delete btn btn-danger confirm']) }}
        {!! Form::close() !!}
    @else
        {!! Form::open(['route' => [$route_prefix.'destroy', 'id' => $item->id], 'class' => 'd-inline-block']) !!}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Trash', ['class' => 'delete btn btn-danger confirm']) }}
        {!! Form::close() !!}
    @endif
</div>