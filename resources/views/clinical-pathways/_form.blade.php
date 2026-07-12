@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-3"><label class="form-label">Kode *</label><input name="code" class="form-control" value="{{ old('code', $pathway?->code) }}" required></div>
    <div class="col-md-9"><label class="form-label">Nama *</label><input name="name" class="form-control" value="{{ old('name', $pathway?->name) }}" required></div>
    <div class="col-md-3"><label class="form-label">Kode Diagnosis</label><input name="diagnosis_code" class="form-control" value="{{ old('diagnosis_code', $pathway?->diagnosis_code) }}"></div>
    <div class="col-md-6"><label class="form-label">Diagnosis</label><input name="diagnosis" class="form-control" value="{{ old('diagnosis', $pathway?->diagnosis) }}"></div>
    <div class="col-md-3"><label class="form-label">Expected LOS (hari)</label><input type="number" name="expected_los_days" class="form-control" value="{{ old('expected_los_days', $pathway?->expected_los_days) }}"></div>
    <div class="col-md-12"><label class="form-label">Kriteria Inklusi</label><textarea name="inclusion_criteria" rows="2" class="form-control">{{ old('inclusion_criteria', $pathway?->inclusion_criteria) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Kriteria Eksklusi</label><textarea name="exclusion_criteria" rows="2" class="form-control">{{ old('exclusion_criteria', $pathway?->exclusion_criteria) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Fase Perawatan (JSON)</label><textarea name="phases_raw" rows="6" class="form-control" placeholder='{"hari_1":["pemeriksaan awal","pemberian antibiotik"],"hari_2":["evaluasi"]}'>{{ old('phases_raw', $pathway?->phases ? json_encode($pathway->phases, JSON_PRETTY_PRINT) : '') }}</textarea></div>
    <div class="col-md-3"><label class="form-label"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $pathway?->is_active ?? true))> Aktif</label></div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e){
    const ta = this.querySelector('[name=phases_raw]');
    if (ta && ta.value.trim()) {
        try {
            const obj = JSON.parse(ta.value);
            for (const [k,v] of Object.entries(obj)) {
                const arr = Array.isArray(v) ? v : [v];
                arr.forEach((item, i) => {
                    const h=document.createElement('input'); h.type='hidden'; h.name=`phases[${k}][${i}]`; h.value=item;
                    this.appendChild(h);
                });
            }
        } catch(err){ e.preventDefault(); alert('JSON fase tidak valid'); }
    }
    if (ta) ta.removeAttribute('name');
});
</script>
