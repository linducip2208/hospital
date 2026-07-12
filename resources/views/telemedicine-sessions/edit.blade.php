@extends('layouts.admin')
@section('title','Edit Telemedicine')
@section('content')
<h1 class="h2">Edit Sesi Telemedicine</h1>
<form action="{{ route('telemedicine-sessions.update', $session) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('telemedicine-sessions._form')</div>
<div class="card-footer text-end"><a href="{{ route('telemedicine-sessions.show', $session) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
