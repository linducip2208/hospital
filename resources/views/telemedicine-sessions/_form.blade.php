@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $session?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Dokter *</label><select name="doctor_id" class="form-select" required><option value="">— Pilih —</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $session?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['scheduled'=>'Terjadwal','ongoing'=>'Berlangsung','completed'=>'Selesai','no_show'=>'Tidak Hadir','cancelled'=>'Batal'] as $k=>$l)<option value="{{ $k }}" @selected(old('status', $session?->status ?? 'scheduled')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Jadwal *</label><input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', $session?->scheduled_at?->format('Y-m-d\TH:i')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Mulai</label><input type="datetime-local" name="started_at" class="form-control" value="{{ old('started_at', $session?->started_at?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-3"><label class="form-label">Selesai</label><input type="datetime-local" name="ended_at" class="form-control" value="{{ old('ended_at', $session?->ended_at?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-3"><label class="form-label">Tarif</label><input type="number" step="0.01" name="fee" class="form-control" value="{{ old('fee', $session?->fee ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Platform</label><input name="platform" class="form-control" placeholder="Zoom / Meet / Teams" value="{{ old('platform', $session?->platform) }}"></div>
    <div class="col-md-4"><label class="form-label">Meeting ID</label><input name="meeting_id" class="form-control" value="{{ old('meeting_id', $session?->meeting_id) }}"></div>
    <div class="col-md-4"><label class="form-label">Meeting URL</label><input type="url" name="meeting_url" class="form-control" value="{{ old('meeting_url', $session?->meeting_url) }}"></div>
    <div class="col-md-12"><label class="form-label">Keluhan Utama</label><textarea name="chief_complaint" rows="2" class="form-control">{{ old('chief_complaint', $session?->chief_complaint) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Asesmen</label><textarea name="assessment" rows="2" class="form-control">{{ old('assessment', $session?->assessment) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Plan</label><textarea name="plan" rows="2" class="form-control">{{ old('plan', $session?->plan) }}</textarea></div>
</div>
