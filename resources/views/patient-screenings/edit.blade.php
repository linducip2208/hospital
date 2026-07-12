@extends('layouts.admin')
@section('title','Edit Skrining')
@section('content')
<h1 class="h2">Edit Skrining</h1>
<form action="{{ route('patient-screenings.update', $screening) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('patient-screenings._form')</div>
<div class="card-footer text-end"><a href="{{ route('patient-screenings.show', $screening) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
