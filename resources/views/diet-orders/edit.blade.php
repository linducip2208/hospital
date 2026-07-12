@extends('layouts.admin')
@section('title','Edit Diet')
@section('content')
<h1 class="h2">Edit Order Diet</h1>
<form action="{{ route('diet-orders.update', $order) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('diet-orders._form')</div>
<div class="card-footer text-end"><a href="{{ route('diet-orders.show', $order) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
