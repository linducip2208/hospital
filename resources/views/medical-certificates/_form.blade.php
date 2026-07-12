@if ($errors->any())
<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Jenis Surat *</label>
        <select name="type" class="form-select" required>
            @foreach($types as $k => $label)
                <option value="{{ $k }}" @selected(old('type', $certificate?->type) === $k)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Terbit *</label>
        <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', $certificate?->issue_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Pasien *</label>
        <select name="patient_id" class="form-select" required>
            <option value="">— Pilih —</option>
            @foreach($patients as $p)
                <option value="{{ $p->id }}" @selected(old('patient_id', $certificate?->patient_id) == $p->id)>{{ $p->name }} ({{ $p->nik ?? '-' }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Dokter</label>
        <select name="doctor_id" class="form-select">
            <option value="">— Pilih —</option>
            @foreach($doctors as $d)
                <option value="{{ $d->id }}" @selected(old('doctor_id', $certificate?->doctor_id) == $d->id)>{{ $d->name }} ({{ $d->str_number }})</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Mulai Istirahat</label>
        <input type="date" name="rest_from" class="form-control" value="{{ old('rest_from', $certificate?->rest_from?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Sampai</label>
        <input type="date" name="rest_until" class="form-control" value="{{ old('rest_until', $certificate?->rest_until?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Lama (hari)</label>
        <input type="number" name="rest_days" min="0" max="365" class="form-control" value="{{ old('rest_days', $certificate?->rest_days) }}">
    </div>
    <div class="col-md-12">
        <label class="form-label">Diagnosis</label>
        <input type="text" name="diagnosis" class="form-control" value="{{ old('diagnosis', $certificate?->diagnosis) }}" maxlength="500">
    </div>
    <div class="col-md-12">
        <label class="form-label">Tujuan / Keperluan</label>
        <input type="text" name="purpose" class="form-control" value="{{ old('purpose', $certificate?->purpose) }}" maxlength="255" placeholder="Misal: lamaran kerja, sekolah, asuransi">
    </div>
    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $certificate?->notes) }}</textarea>
    </div>
    @if($certificate)
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['draft','issued','cancelled'] as $s)
                <option value="{{ $s }}" @selected(old('status', $certificate->status) === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    @endif
</div>
