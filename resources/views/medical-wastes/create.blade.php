@extends('layouts.admin')
@section('title', 'Buat Manifest Limbah')
@section('content')
<div class="page-header"><h1 class="h2">Buat Manifest Limbah Medis</h1></div>
<form action="{{ route('medical-wastes.store') }}" method="POST">@csrf
    @include('medical-wastes._form', ['waste' => null])
</form>
@endsection
