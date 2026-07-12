@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $case?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Jenis Infeksi *</label><select name="infection_type" class="form-select">@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(old('infection_type', $case?->infection_type)===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Tgl Deteksi *</label><input type="date" name="detection_date" class="form-control" value="{{ old('detection_date', $case?->detection_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Tgl Onset</label><input type="date" name="onset_date" class="form-control" value="{{ old('onset_date', $case?->onset_date?->format('Y-m-d')) }}"></div>
    <div class="col-md-3"><label class="form-label">Outcome *</label><select name="outcome" class="form-select">@foreach(['ongoing'=>'Ongoing','resolved'=>'Resolved','died'=>'Meninggal'] as $k=>$l)<option value="{{ $k }}" @selected(old('outcome', $case?->outcome ?? 'ongoing')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Organisme</label><input name="organism" class="form-control" value="{{ old('organism', $case?->organism) }}"></div>
    <div class="col-md-12"><label class="form-label">Lokasi/Site *</label><input name="site" class="form-control" value="{{ old('site', $case?->site) }}" required></div>
    <div class="col-md-12"><label class="form-label">Gejala</label><textarea name="symptoms" rows="2" class="form-control">{{ old('symptoms', $case?->symptoms) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Terapi Antibiotik</label><textarea name="antibiotic_therapy" rows="2" class="form-control">{{ old('antibiotic_therapy', $case?->antibiotic_therapy) }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Intervensi</label><textarea name="intervention" rows="2" class="form-control">{{ old('intervention', $case?->intervention) }}</textarea></div>
</div>
