@extends('layouts.admin')
@section('title','Lapor IKP')
@section('content')
<h1 class="h2">Lapor Insiden Keselamatan Pasien</h1>
<form action="{{ route('patient-safety-incidents.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('patient-safety-incidents._form', ['incident' => null])</div>
<div class="card-footer text-end"><a href="{{ route('patient-safety-incidents.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
