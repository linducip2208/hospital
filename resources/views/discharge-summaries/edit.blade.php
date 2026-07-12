@extends('layouts.admin')
@section('title','Edit Resume')
@section('content')
<h1 class="h2">Edit Resume Medis</h1>
<form action="{{ route('discharge-summaries.update', $summary) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('discharge-summaries._form')</div>
<div class="card-footer text-end"><a href="{{ route('discharge-summaries.show', $summary) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
