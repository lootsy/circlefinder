@extends('admin.layouts.app')

@section('title', 'Edit ' . $item)

@section('content')
    @include('admin.users.form', ['item' => $item])
@endsection