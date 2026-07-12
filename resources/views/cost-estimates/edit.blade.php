@extends('layouts.admin')
@section('title','Edit Estimasi')
@section('content')
<h1 class="h2">Edit Estimasi Biaya</h1>
<form action="{{ route('cost-estimates.update', $estimate) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('cost-estimates._form')</div>
<div class="card-footer text-end"><a href="{{ route('cost-estimates.show', $estimate) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
