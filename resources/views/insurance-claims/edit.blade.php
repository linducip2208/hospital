@extends('layouts.admin')
@section('title','Edit Klaim')
@section('content')
<h1 class="h2">Edit Klaim</h1>
<form action="{{ route('insurance-claims.update', $claim) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('insurance-claims._form')</div>
<div class="card-footer text-end"><a href="{{ route('insurance-claims.show', $claim) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
