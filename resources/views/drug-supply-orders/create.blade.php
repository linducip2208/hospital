@extends('layouts.admin')
@section('title', 'Buat SP')
@section('content')
<h1 class="h2">Buat Surat Pesanan</h1>
<form action="{{ route('drug-supply-orders.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('drug-supply-orders._form', ['order' => null])</div>
<div class="card-footer text-end"><a href="{{ route('drug-supply-orders.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
