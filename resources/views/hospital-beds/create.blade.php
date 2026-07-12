@extends('layouts.admin')
@section('title','Tambah Bed')
@section('content')
<h1 class="h2">Tambah Bed</h1>
<form action="{{ route('hospital-beds.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('hospital-beds._form', ['bed' => null])</div>
<div class="card-footer text-end"><a href="{{ route('hospital-beds.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
