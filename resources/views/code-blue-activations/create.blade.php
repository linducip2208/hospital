@extends('layouts.admin')
@section('title','Aktivasi Code Blue')
@section('content')
<h1 class="h2">Aktivasi Code Blue Baru</h1>
<form action="{{ route('code-blue-activations.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('code-blue-activations._form', ['code' => null])</div>
<div class="card-footer text-end"><a href="{{ route('code-blue-activations.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
