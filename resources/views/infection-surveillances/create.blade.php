@extends('layouts.admin')
@section('title','Catat HAI')
@section('content')
<h1 class="h2">Catat Kasus Infeksi</h1>
<form action="{{ route('infection-surveillances.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('infection-surveillances._form', ['case' => null])</div>
<div class="card-footer text-end"><a href="{{ route('infection-surveillances.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
