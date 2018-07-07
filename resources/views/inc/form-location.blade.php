@if(\App\Country::count())
<h2>Location</h2>

    {{ Form::label('country', 'Country') }}
    {{ Form::select('country', list_of_countries(), null, ['class' => 'form-control', 'placeholder' => '']) }}

@endif