@extends('layouts.admin')
@section('title', 'Edit Kalibrasi')
@section('content')
<div class="page-header"><h1 class="h2">Edit Kalibrasi</h1></div>
<form action="{{ route('equipment-calibrations.update', $calibration) }}" method="POST">@csrf @method('PUT')
    @include('equipment-calibrations._form', ['calibration' => $calibration])
</form>
@endsection
