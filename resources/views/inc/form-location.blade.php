@if(\App\Country::count())
<h2>Location</h2>
<div class="form-group">
    {{ Form::label('country', 'Country') }}
    {{ Form::select('country', list_of_countries(), null, ['class' => 'form-control', 'placeholder' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('state', 'State') }}
    {{ Form::select('state', list_of_states(\App\Country::where('sortname', 'DE')->first()), null, ['class' => 'form-control', 'placeholder' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('city', 'City') }}
    {{ Form::select('city', list_of_cities(\App\State::first()), null, ['class' => 'form-control', 'placeholder' => '']) }}
</div>
@endif