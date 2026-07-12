@extends('layouts.admin')
@section('title', 'Edit Resep')
@section('content')
<h1 class="h2">Edit Resep</h1>
<form action="{{ route('prescriptions.update', $prescription) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
    <div class="card-body">@include('prescriptions._form')</div>
    <div class="card-footer text-end"><a href="{{ route('prescriptions.show', $prescription) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
