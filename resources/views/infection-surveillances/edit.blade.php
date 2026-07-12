@extends('layouts.admin')
@section('title','Edit HAI')
@section('content')
<h1 class="h2">Edit Kasus Infeksi</h1>
<form action="{{ route('infection-surveillances.update', $case) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('infection-surveillances._form')</div>
<div class="card-footer text-end"><a href="{{ route('infection-surveillances.show', $case) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
