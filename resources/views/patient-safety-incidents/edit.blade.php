@extends('layouts.admin')
@section('title','Edit IKP')
@section('content')
<h1 class="h2">Edit IKP</h1>
<form action="{{ route('patient-safety-incidents.update', $incident) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('patient-safety-incidents._form')</div>
<div class="card-footer text-end"><a href="{{ route('patient-safety-incidents.show', $incident) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
