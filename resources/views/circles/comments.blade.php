<div class="card">
    <h5 class="card-header">Comments</h5>
    
    <div class="card-body">
        @if(count($messages))

        @else
        <p>No comments yet</p>
        @endif

        <div class="mt-4">
            {!! Form::open(['route' => ['circles.messages.store', 'uuid' => $item->uuid]]) !!}
                <div class="form-group">
                    {{ Form::label('body', 'Comment') }}
                    {{ Form::textarea('body', null, ['class' => 'form-control', 'required' => true, 'rows' => 3]) }}
                </div>
            
                {{ Form::submit('Post comment', ['class' => 'btn btn-success']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>

