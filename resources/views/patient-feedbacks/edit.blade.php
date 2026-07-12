@extends('layouts.admin')
@section('title','Edit Feedback')
@section('content')
<h1 class="h2">Edit Feedback</h1>
<form action="{{ route('patient-feedbacks.update', $feedback) }}" method="POST" class="card shadow-sm">@csrf @method('PUT')
<div class="card-body">@include('patient-feedbacks._form')</div>
<div class="card-footer text-end"><a href="{{ route('patient-feedbacks.show', $feedback) }}" class="btn btn-secondary">Batal</a> <button class="btn btn-primary">Update</button></div>
</form>
@endsection
