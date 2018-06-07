@extends('admin.layouts.app')

@section('title', 'Edit ' . $item)

@section('content')
    @include('admin.roles.form', ['item' => $item])
@endsection