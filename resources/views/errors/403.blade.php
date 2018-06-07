@extends('layouts.app')

@section('content')
    <h1>Error: {{ $exception->getMessage() }}</h1>
@endsection