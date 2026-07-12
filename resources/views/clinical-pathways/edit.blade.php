@extends('layouts.admin')
@section('title','Edit Pathway')
@section('content')
<h1 class="h2">Edit Pathway</h1>
<form action="{{ route('clinical-pathways.update', $pathway) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('clinical-pathways._form')</div>
<div class="card-footer text-end"><a href="{{ route('clinical-pathways.show', $pathway) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
