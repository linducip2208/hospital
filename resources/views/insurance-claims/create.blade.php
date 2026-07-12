@extends('layouts.admin')
@section('title','Buat Klaim')
@section('content')
<h1 class="h2">Buat Klaim Asuransi</h1>
<form action="{{ route('insurance-claims.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('insurance-claims._form', ['claim' => null])</div>
<div class="card-footer text-end"><a href="{{ route('insurance-claims.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
