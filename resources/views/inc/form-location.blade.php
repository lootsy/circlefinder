@if(\App\Country::count())
<h2>Location</h2>
<div class="form-group">
    {{ Form::label('country', 'Country') }}
    {{ Form::select('country', list_of_countries(), null, ['class' => 'form-control', 'placeholder' => '']) }}
</div>
<div class="form-group">
    {{ Form::label('city', 'City') }}
    {{ Form::select('city', [], null, ['class' => 'form-control', 'placeholder' => '']) }}
</div>
@endif