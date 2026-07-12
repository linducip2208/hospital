@extends('layouts.admin')
@section('title','Order Diet')
@section('content')
<h1 class="h2">Order Diet Baru</h1>
<form action="{{ route('diet-orders.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('diet-orders._form', ['order' => null])</div>
<div class="card-footer text-end"><a href="{{ route('diet-orders.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
