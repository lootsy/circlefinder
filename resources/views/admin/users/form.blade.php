@if(isset($item))
    {!! Form::model($item, ['route' => ['admin.users.update', 'id' => $item->id], 'method' => 'put']) !!}
@else
    {!! Form::open(['route' => 'admin.users.store']) !!}
@endif

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
    </div>
    
    <div class="form-group">
        {{ Form::label('email', 'E-Mail') }}
        {{ Form::email('email', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('about', 'About') }}
        {{ Form::textarea('about', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('twitter_profile_url', 'Twitter') }}
        {{ Form::text('twitter_profile_url', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('facebook_profile_url', 'Facebook') }}
        {{ Form::text('facebook_profile_url', null, ['class' => 'form-control']) }}
    </div>

    <div class="form-group">
        {{ Form::label('yammer_profile_url', 'Yammer') }}
        {{ Form::text('yammer_profile_url', null, ['class' => 'form-control']) }}
    </div>


    <div class="form-group">
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['class' => 'form-control']) }}
    </div>

    @if(\App\Role::count())
    <h2>Roles</h2>
    <ul class="form-check">
        @foreach(\App\Role::all() as $role)
        <li>
            {{ Form::checkbox('roles[]', $role->id, $item->roles->contains($role), ['class' => 'form-check-input', 'id' => 'role-' . $role->id]) }} 
            {{ Form::label('role-' . $role->id, $role->title) }}
        </li>
        @endforeach
    </ul>
    @endif

    
    @if(isset($item))
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    @else
        {{ Form::submit('Create', ['class' => 'btn btn-success']) }}
    @endif

@if(isset($item) && $item->trashed())
    <a href="{{ route('admin.users.trash') }}" class="btn btn-light">Cancel</a>
@else
    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
@endif

{!! Form::close() !!}