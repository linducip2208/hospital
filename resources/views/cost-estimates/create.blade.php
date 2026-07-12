@extends('layouts.admin')
@section('title','Buat Estimasi')
@section('content')
<h1 class="h2">Buat Estimasi Biaya</h1>
<form action="{{ route('cost-estimates.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('cost-estimates._form', ['estimate' => null])</div>
<div class="card-footer text-end"><a href="{{ route('cost-estimates.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
