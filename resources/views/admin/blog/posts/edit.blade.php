@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
<div class="d-flex justify-content-between align-items-center page-header">
    <h1 class="h2">Edit Artikel</h1>
    <a href="{{ route('admin.blog.posts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<form action="{{ route('admin.blog.posts.update', $post) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.blog.posts._form', ['post' => $post])
</form>
@endsection
