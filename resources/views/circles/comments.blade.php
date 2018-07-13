<div class="card">
    <h5 class="card-header">Comments</h5>
    
    <div class="card-body">
        @if($membership || $user->moderator())
            @if(count($messages))
            <div class="list-group list-group-flush mb-4">
                @foreach($messages as $message)
                    <div class="list-group-item">
                        <strong>{{ $message->user->name }}</strong>
                        <div>{{ $message->body }}</div>
                    </div>
                @endforeach
            </div>
            @else
            <p>No visible comments yet</p>
            @endif
        @endif

        @can('create', [\App\Message::class, $item])
        <div>
            {!! Form::open(['route' => ['circles.messages.store', 'uuid' => $item->uuid]]) !!}
                <div class="form-group">
                    {{ Form::label('body', 'Comment') }}
                    {{ Form::textarea('body', null, ['class' => 'form-control', 'required' => true, 'rows' => 2]) }}
                </div>

                <div class="form-group">
                    {{ Form::checkbox('show_to_all', true, null, ['id' => 'show_to_all']) }}
                    {{ Form::label('show_to_all', 'Show to all members') }}
                </div>
            
                {{ Form::submit('Post comment', ['class' => 'btn btn-success']) }}

                <div class="text-info mt-3"><span class="fa fa-eye"></span> New members will see the comment only if "Show all members" is checked.</div>
            {!! Form::close() !!}
        </div>
        @else
        <div><span class="fa fa-lock"></span> Only circle members can read and post comments!</div>
        @endcan
    </div>
</div>

