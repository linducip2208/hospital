@extends('layouts.admin')
@section('title','Edit Code Blue')
@section('content')
<h1 class="h2">Edit Code Blue</h1>
<form action="{{ route('code-blue-activations.update', $code) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('code-blue-activations._form')</div>
<div class="card-footer text-end"><a href="{{ route('code-blue-activations.show', $code) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
