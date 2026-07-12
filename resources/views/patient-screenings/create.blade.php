@extends('layouts.admin')
@section('title','Buat Skrining')
@section('content')
<h1 class="h2">Buat Skrining</h1>
<form action="{{ route('patient-screenings.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('patient-screenings._form', ['screening' => null])</div>
<div class="card-footer text-end"><a href="{{ route('patient-screenings.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
