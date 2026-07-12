@extends('layouts.admin')
@section('title','Edit Maintenance')
@section('content')
<h1 class="h2">Edit Maintenance</h1>
<form action="{{ route('equipment-maintenances.update', $maintenance) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('equipment-maintenances._form')</div>
<div class="card-footer text-end"><a href="{{ route('equipment-maintenances.show', $maintenance) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
