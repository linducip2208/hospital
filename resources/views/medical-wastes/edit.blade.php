@extends('layouts.admin')
@section('title', 'Edit Manifest Limbah')
@section('content')
<div class="page-header"><h1 class="h2">Edit Manifest Limbah Medis</h1></div>
<form action="{{ route('medical-wastes.update', $waste) }}" method="POST">@csrf @method('PUT')
    @include('medical-wastes._form', ['waste' => $waste])
</form>
@endsection
