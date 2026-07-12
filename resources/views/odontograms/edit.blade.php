@extends('layouts.admin')
@section('title','Edit Odontogram')
@section('content')
<h1 class="h2">Edit Odontogram</h1>
<form action="{{ route('odontograms.update', $odontogram) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('odontograms._form')</div>
<div class="card-footer text-end"><a href="{{ route('odontograms.show', $odontogram) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
