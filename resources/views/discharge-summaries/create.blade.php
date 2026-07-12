@extends('layouts.admin')
@section('title','Buat Resume')
@section('content')
<h1 class="h2">Buat Resume Medis Pulang</h1>
<form action="{{ route('discharge-summaries.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('discharge-summaries._form', ['summary' => null])</div>
<div class="card-footer text-end"><a href="{{ route('discharge-summaries.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
