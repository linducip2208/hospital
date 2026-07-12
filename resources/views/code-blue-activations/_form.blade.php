@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Pasien</label><select name="patient_id" class="form-select"><option value="">— Tidak terdaftar —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $code?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-6"><label class="form-label">Lokasi *</label><input name="location" class="form-control" value="{{ old('location', $code?->location) }}" required></div>
    <div class="col-md-3"><label class="form-label">Waktu Aktivasi *</label><input type="datetime-local" name="activation_time" class="form-control" value="{{ old('activation_time', $code?->activation_time?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required></div>
    <div class="col-md-3"><label class="form-label">Tim Tiba</label><input type="datetime-local" name="team_arrival_time" class="form-control" value="{{ old('team_arrival_time', $code?->team_arrival_time?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-3"><label class="form-label">ROSC</label><input type="datetime-local" name="return_circulation_time" class="form-control" value="{{ old('return_circulation_time', $code?->return_circulation_time?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-3"><label class="form-label">Selesai</label><input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time', $code?->end_time?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-4"><label class="form-label">Outcome *</label><select name="outcome" class="form-select">@foreach(['rosc'=>'ROSC','died'=>'Meninggal','transferred'=>'Dipindah','ongoing'=>'Berlangsung'] as $k=>$l)<option value="{{ $k }}" @selected(old('outcome', $code?->outcome ?? 'ongoing')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Team Leader</label><input name="team_leader" class="form-control" value="{{ old('team_leader', $code?->team_leader) }}"></div>
    <div class="col-md-4"><label class="form-label">Initial Rhythm</label><input name="initial_rhythm" class="form-control" value="{{ old('initial_rhythm', $code?->initial_rhythm) }}" placeholder="VF / VT / PEA / Asystole"></div>
    <div class="col-md-12"><label class="form-label">Intervensi</label><textarea name="interventions" rows="2" class="form-control">{{ old('interventions', $code?->interventions) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Obat yang Diberikan</label><textarea name="medications_given" rows="2" class="form-control">{{ old('medications_given', $code?->medications_given) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $code?->notes) }}</textarea></div>
</div>
