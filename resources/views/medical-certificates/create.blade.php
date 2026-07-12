@extends('layouts.admin')
@section('title', 'Buat Surat')
@section('content')
<h1 class="h2">Buat Surat Keterangan</h1>
<form action="{{ route('medical-certificates.store') }}" method="POST" class="card shadow-sm">
    @csrf
    <div class="card-body">
        @include('medical-certificates._form', ['certificate' => null])
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('medical-certificates.index') }}" class="btn btn-secondary">Batal</a>
        <button class="btn btn-primary">Simpan</button>
    </div>
</form>
@endsection
