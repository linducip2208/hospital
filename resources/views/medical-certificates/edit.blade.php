@extends('layouts.admin')
@section('title', 'Edit Surat')
@section('content')
<h1 class="h2">Edit Surat Keterangan</h1>
<form action="{{ route('medical-certificates.update', $certificate) }}" method="POST" class="card shadow-sm">
    @csrf @method('PUT')
    <div class="card-body">
        @include('medical-certificates._form', ['certificate' => $certificate])
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('medical-certificates.show', $certificate) }}" class="btn btn-secondary">Batal</a>
        <button class="btn btn-primary">Update</button>
    </div>
</form>
@endsection
