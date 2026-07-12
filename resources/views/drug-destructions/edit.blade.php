@extends('layouts.admin')
@section('title', 'Edit BA Pemusnahan')
@section('content')
<h1 class="h2">Edit BA Pemusnahan</h1>
<form action="{{ route('drug-destructions.update', $destruction) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('drug-destructions._form')</div>
<div class="card-footer text-end"><a href="{{ route('drug-destructions.show', $destruction) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
