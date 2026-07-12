@extends('layouts.admin')
@section('title','Feedback')
@section('content')
<h1 class="h2">Tambah Feedback</h1>
<form action="{{ route('patient-feedbacks.store') }}" method="POST" class="card shadow-sm">@csrf
<div class="card-body">@include('patient-feedbacks._form', ['feedback' => null])</div>
<div class="card-footer text-end"><a href="{{ route('patient-feedbacks.index') }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Simpan</button></div>
</form>
@endsection
