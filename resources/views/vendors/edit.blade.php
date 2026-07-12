@extends('layouts.admin')
@section('title', 'Edit Vendor')
@section('content')
<div class="page-header"><h1 class="h2">Edit Vendor</h1></div>
<form action="{{ route('vendors.update', $vendor) }}" method="POST">@csrf @method('PUT')
    @include('vendors._form', ['vendor' => $vendor])
</form>
@endsection
