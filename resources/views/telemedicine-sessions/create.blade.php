@extends('layouts.admin')
@section('title','Jadwalkan Telemedicine')
@section('content')
<h1 class="h2">Jadwalkan Telemedicine</h1>
<form action="{{ route('telemedicine-sessions.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('telemedicine-sessions._form', ['session' => null])</div>
<div class="card-footer text-end"><a href="{{ route('telemedicine-sessions.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
