@extends('layouts.admin')
@section('title','Periksa Gigi')
@section('content')
<h1 class="h2">Pemeriksaan Gigi (Odontogram)</h1>
<form action="{{ route('odontograms.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('odontograms._form', ['odontogram' => null])</div>
<div class="card-footer text-end"><a href="{{ route('odontograms.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
