@extends('layouts.admin')
@section('title','Jadwal Maintenance')
@section('content')
<h1 class="h2">Jadwal Maintenance</h1>
<form action="{{ route('equipment-maintenances.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('equipment-maintenances._form', ['maintenance' => null])</div>
<div class="card-footer text-end"><a href="{{ route('equipment-maintenances.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
