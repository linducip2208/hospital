@extends('layouts.admin')
@section('title','Catat Vital ICU')
@section('content')
<h1 class="h2">Catat Tanda Vital ICU</h1>
<form action="{{ route('icu-monitorings.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('icu-monitorings._form', ['monitoring' => null])</div>
<div class="card-footer text-end"><a href="{{ route('icu-monitorings.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
