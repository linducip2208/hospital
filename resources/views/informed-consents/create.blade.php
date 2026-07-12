@extends('layouts.admin')
@section('title', 'Buat Surat Persetujuan')
@section('content')
<h1 class="h2">Buat Surat Persetujuan / Penolakan</h1>
<form action="{{ route('informed-consents.store') }}" method="POST" class="card shadow-sm">@csrf
    <div class="card-body">@include('informed-consents._form', ['consent' => null])</div>
    <div class="card-footer text-end"><a href="{{ route('informed-consents.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
