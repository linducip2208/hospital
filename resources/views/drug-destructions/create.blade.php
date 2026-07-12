@extends('layouts.admin')
@section('title', 'Buat BA Pemusnahan')
@section('content')
<h1 class="h2">Buat Berita Acara Pemusnahan</h1>
<form action="{{ route('drug-destructions.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('drug-destructions._form', ['destruction' => null])</div>
<div class="card-footer text-end"><a href="{{ route('drug-destructions.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
