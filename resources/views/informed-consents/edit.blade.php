@extends('layouts.admin')
@section('title', 'Edit Surat')
@section('content')
<h1 class="h2">Edit Surat Persetujuan</h1>
<form action="{{ route('informed-consents.update', $consent) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
    <div class="card-body">@include('informed-consents._form', ['consent' => $consent])</div>
    <div class="card-footer text-end"><a href="{{ route('informed-consents.show', $consent) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
