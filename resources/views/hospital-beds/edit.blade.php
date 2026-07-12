@extends('layouts.admin')
@section('title','Edit Bed')
@section('content')
<h1 class="h2">Edit Bed</h1>
<form action="{{ route('hospital-beds.update', $bed) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('hospital-beds._form')</div>
<div class="card-footer text-end"><a href="{{ route('hospital-beds.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
