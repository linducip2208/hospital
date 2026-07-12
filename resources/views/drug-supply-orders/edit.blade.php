@extends('layouts.admin')
@section('title', 'Edit SP')
@section('content')
<h1 class="h2">Edit SP</h1>
<form action="{{ route('drug-supply-orders.update', $order) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('drug-supply-orders._form')</div>
<div class="card-footer text-end"><a href="{{ route('drug-supply-orders.show', $order) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
