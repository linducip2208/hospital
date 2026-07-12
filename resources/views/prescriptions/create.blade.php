@extends('layouts.admin')
@section('title', 'Buat Resep')
@section('content')
<h1 class="h2">Buat Resep</h1>
<form action="{{ route('prescriptions.store') }}" method="POST" class="card shadow-sm">@csrf
    <div class="card-body">@include('prescriptions._form', ['prescription' => null])</div>
    <div class="card-footer text-end"><a href="{{ route('prescriptions.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
