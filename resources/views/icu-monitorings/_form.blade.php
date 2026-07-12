@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $monitoring?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Bed</label><select name="hospital_bed_id" class="form-select"><option value="">— Pilih —</option>@foreach($beds as $b)<option value="{{ $b->id }}" @selected(old('hospital_bed_id', $monitoring?->hospital_bed_id)==$b->id)>{{ $b->room?->name }} / {{ $b->bed_code }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Waktu *</label><input type="datetime-local" name="recorded_at" class="form-control" value="{{ old('recorded_at', $monitoring?->recorded_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required></div>
    <div class="col-md-2"><label class="form-label">Suhu (°C)</label><input type="number" step="0.1" name="temperature" class="form-control" value="{{ old('temperature', $monitoring?->temperature) }}"></div>
    <div class="col-md-2"><label class="form-label">HR</label><input type="number" name="hr" class="form-control" value="{{ old('hr', $monitoring?->hr) }}"></div>
    <div class="col-md-2"><label class="form-label">RR</label><input type="number" name="rr" class="form-control" value="{{ old('rr', $monitoring?->rr) }}"></div>
    <div class="col-md-2"><label class="form-label">SBP</label><input type="number" name="sbp" class="form-control" value="{{ old('sbp', $monitoring?->sbp) }}"></div>
    <div class="col-md-2"><label class="form-label">DBP</label><input type="number" name="dbp" class="form-control" value="{{ old('dbp', $monitoring?->dbp) }}"></div>
    <div class="col-md-2"><label class="form-label">MAP</label><input type="number" name="map" class="form-control" value="{{ old('map', $monitoring?->map) }}"></div>
    <div class="col-md-2"><label class="form-label">SpO2 (%)</label><input type="number" name="spo2" class="form-control" value="{{ old('spo2', $monitoring?->spo2) }}"></div>
    <div class="col-md-2"><label class="form-label">GCS</label><input type="number" name="gcs" min="3" max="15" class="form-control" value="{{ old('gcs', $monitoring?->gcs) }}"></div>
    <div class="col-md-2"><label class="form-label">CVP</label><input type="number" step="0.1" name="cvp" class="form-control" value="{{ old('cvp', $monitoring?->cvp) }}"></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $monitoring?->notes) }}</textarea></div>
</div>
