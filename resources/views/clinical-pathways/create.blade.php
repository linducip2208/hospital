@extends('layouts.admin')
@section('title','Buat Pathway')
@section('content')
<h1 class="h2">Buat Clinical Pathway</h1>
<form action="{{ route('clinical-pathways.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('clinical-pathways._form', ['pathway' => null])</div>
<div class="card-footer text-end"><a href="{{ route('clinical-pathways.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
