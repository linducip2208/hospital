@extends('portal.layout')

@section('title', 'Rekam Medis')

@section('content')
<h1 class="h4 fw-bold mb-3">Rekam Medis Saya</h1>

<div class="card-soft p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Tanggal</th><th>Dokter</th><th>Diagnosis</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                <tr>
                    <td>{{ $rec->created_at?->format('d M Y') }}</td>
                    <td>dr. {{ $rec->doctor?->name ?? '-' }}</td>
                    <td>{{ Str::limit($rec->diagnosis, 60) ?: '-' }}</td>
                    <td><a href="{{ route('portal.medical-records.show', $rec) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada rekam medis</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $records->links() }}</div>
@endsection
