@extends('layouts.admin')
@section('title', 'Notifikasi')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><span class="text-uppercase small text-primary fw-bold">Notification center</span><h1 class="h3 mb-0">Notifikasi Operasional</h1></div>
    <form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button class="btn btn-outline-primary"><i class="bi bi-check2-all"></i> Tandai semua dibaca</button></form>
</div>
<div class="card shadow-sm border-0"><div class="list-group list-group-flush">
@forelse($notifications as $notification)
<div class="list-group-item d-flex justify-content-between gap-3 {{ $notification->read_at ? '' : 'bg-primary-subtle' }}">
    <div><div class="fw-semibold">{{ data_get($notification->data, 'message', 'Notifikasi sistem') }}</div><small class="text-muted">{{ $notification->created_at->format('d M Y H:i') }}</small></div>
    @if(! $notification->read_at)<form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button class="btn btn-sm btn-link">Sudah dibaca</button></form>@endif
</div>
@empty<div class="list-group-item text-muted py-5 text-center"><i class="bi bi-bell-slash fs-2 d-block mb-2"></i>Belum ada notifikasi.</div>@endforelse
</div><div class="card-footer bg-transparent">{{ $notifications->withQueryString()->links() }}</div></div>
@endsection
