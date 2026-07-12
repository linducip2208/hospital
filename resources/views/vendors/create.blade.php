@extends('layouts.admin')
@section('title', 'Tambah Vendor')
@section('content')
<div class="page-header"><h1 class="h2">Tambah Vendor</h1></div>
<form action="{{ route('vendors.store') }}" method="POST">@csrf
    @include('vendors._form', ['vendor' => null])
</form>
@endsection
