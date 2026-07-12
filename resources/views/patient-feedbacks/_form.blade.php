@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Pasien</label><select name="patient_id" class="form-select"><option value="">— Anonim —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $feedback?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Tgl Kunjungan</label><input type="date" name="visit_date" class="form-control" value="{{ old('visit_date', $feedback?->visit_date?->format('Y-m-d')) }}"></div>
    <div class="col-md-4"><label class="form-label">Layanan</label><input name="service_type" class="form-control" placeholder="Rawat Jalan / IGD / Inap" value="{{ old('service_type', $feedback?->service_type) }}"></div>
    @foreach(['rating_overall'=>'Pelayanan Keseluruhan','rating_doctor'=>'Dokter','rating_nurse'=>'Perawat','rating_facility'=>'Fasilitas','rating_cleanliness'=>'Kebersihan','rating_speed'=>'Kecepatan'] as $k => $l)
    <div class="col-md-4">
        <label class="form-label">{{ $l }}</label>
        <select name="{{ $k }}" class="form-select">
            <option value="">—</option>
            @for($i=1; $i<=5; $i++)<option value="{{ $i }}" @selected(old($k, $feedback?->{$k})==$i)>{{ str_repeat('★', $i) }} ({{ $i }})</option>@endfor
        </select>
    </div>
    @endforeach
    <div class="col-md-4"><label class="form-label">Akan Merekomendasikan?</label><select name="would_recommend" class="form-select"><option value="">—</option><option value="1" @selected(old('would_recommend', $feedback?->would_recommend)===true)>Ya</option><option value="0" @selected(old('would_recommend', $feedback?->would_recommend)===false)>Tidak</option></select></div>
    <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['new'=>'Baru','reviewed'=>'Direview','responded'=>'Direspon','closed'=>'Selesai'] as $k=>$l)<option value="{{ $k }}" @selected(old('status', $feedback?->status ?? 'new')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-12"><label class="form-label">Yang Disukai</label><textarea name="positive" rows="2" class="form-control">{{ old('positive', $feedback?->positive) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Yang Perlu Diperbaiki</label><textarea name="negative" rows="2" class="form-control">{{ old('negative', $feedback?->negative) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Saran</label><textarea name="suggestion" rows="2" class="form-control">{{ old('suggestion', $feedback?->suggestion) }}</textarea></div>
    <div class="col-md-4"><label class="form-label"><input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous', $feedback?->is_anonymous))> Anonim</label></div>
    <div class="col-md-4"><label class="form-label">Nama Responden (opsional)</label><input name="respondent_name" class="form-control" value="{{ old('respondent_name', $feedback?->respondent_name) }}"></div>
    <div class="col-md-4"><label class="form-label">Kontak (opsional)</label><input name="respondent_contact" class="form-control" value="{{ old('respondent_contact', $feedback?->respondent_contact) }}"></div>
</div>
