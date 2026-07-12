@extends('layouts.admin')
@section('title', 'Catat Kalibrasi')
@section('content')
<div class="page-header"><h1 class="h2">Catat Kalibrasi</h1></div>
<form action="{{ route('equipment-calibrations.store') }}" method="POST">@csrf
    @include('equipment-calibrations._form', ['calibration' => null])
</form>
@endsection
