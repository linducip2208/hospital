@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Jenis Surat *</label>
        <select name="kind" class="form-select" required>
            @foreach($kinds as $k => $label)<option value="{{ $k }}" @selected(old('kind', $consent?->kind) === $k)>{{ $label }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tanggal Tanda Tangan</label>
        <input type="datetime-local" name="signed_at" class="form-control" value="{{ old('signed_at', $consent?->signed_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Pasien *</label>
        <select name="patient_id" class="form-select" required>
            <option value="">— Pilih —</option>
            @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $consent?->patient_id) == $p->id)>{{ $p->name }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Dokter / DPJP</label>
        <select name="doctor_id" class="form-select">
            <option value="">— Pilih —</option>
            @foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $consent?->doctor_id) == $d->id)>{{ $d->name }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label class="form-label">Nama Tindakan / Prosedur *</label>
        <input type="text" name="procedure_name" class="form-control" value="{{ old('procedure_name', $consent?->procedure_name) }}" required maxlength="255">
    </div>
    <div class="col-md-12">
        <label class="form-label">Penjelasan Tindakan</label>
        <textarea name="procedure_description" rows="3" class="form-control">{{ old('procedure_description', $consent?->procedure_description) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Risiko</label>
        <textarea name="risks" rows="3" class="form-control">{{ old('risks', $consent?->risks) }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Alternatif Tindakan</label>
        <textarea name="alternatives" rows="3" class="form-control">{{ old('alternatives', $consent?->alternatives) }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Penandatangan</label>
        <input type="text" name="signed_by_name" class="form-control" value="{{ old('signed_by_name', $consent?->signed_by_name) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Hubungan dengan Pasien</label>
        <input type="text" name="signed_by_relation" class="form-control" placeholder="Diri sendiri / Suami / Istri / Ayah / Ibu" value="{{ old('signed_by_relation', $consent?->signed_by_relation) }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Saksi</label>
        <input type="text" name="witness_name" class="form-control" value="{{ old('witness_name', $consent?->witness_name) }}">
    </div>
    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $consent?->notes) }}</textarea>
    </div>
</div>
