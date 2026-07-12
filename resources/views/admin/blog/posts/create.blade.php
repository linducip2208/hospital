@extends('layouts.admin')

@section('title', 'Tulis Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Tulis Artikel</h1>
    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.blog.posts.store') }}" method="POST">
    @csrf
    @include('admin.blog.posts._form', ['post' => null])
</form>
@endsection
