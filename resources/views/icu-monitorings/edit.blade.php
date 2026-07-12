@extends('layouts.admin')
@section('title','Edit Vital ICU')
@section('content')
<h1 class="h2">Edit Vital ICU</h1>
<form action="{{ route('icu-monitorings.update', $monitoring) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('icu-monitorings._form')</div>
<div class="card-footer text-end"><a href="{{ route('icu-monitorings.show', $monitoring) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
