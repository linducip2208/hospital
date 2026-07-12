@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Jenis Skrining *</label><select name="type" class="form-select" required>@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(old('type', $screening?->type)===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id',$screening?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Tanggal *</label><input type="datetime-local" name="screened_at" class="form-control" value="{{ old('screened_at', $screening?->screened_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" required></div>
    <div class="col-md-4"><label class="form-label">Skor</label><input type="number" name="score" min="0" max="1000" class="form-control" value="{{ old('score', $screening?->score ?? 0) }}"></div>
    <div class="col-md-4"><label class="form-label">Tingkat Risiko *</label><select name="risk_level" class="form-select">@foreach(['low'=>'Rendah','moderate'=>'Sedang','high'=>'Tinggi'] as $k=>$l)<option value="{{ $k }}" @selected(old('risk_level',$screening?->risk_level)===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-12"><label class="form-label">Jawaban / Detail (JSON, optional)</label><textarea name="answers_raw" rows="3" class="form-control" placeholder='{"riwayat_jatuh":"ya","skor_morse":45}'>{{ old('answers_raw', $screening?->answers ? json_encode($screening->answers, JSON_PRETTY_PRINT) : '') }}</textarea><div class="form-text">Format JSON, boleh kosong.</div></div>
    <div class="col-md-12"><label class="form-label">Intervensi</label><textarea name="intervention" rows="2" class="form-control">{{ old('intervention', $screening?->intervention) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $screening?->notes) }}</textarea></div>
</div>
<script>
document.querySelector('form').addEventListener('submit', function(e){
    const ta = this.querySelector('[name=answers_raw]');
    if (ta.value.trim()) {
        try {
            const parsed = JSON.parse(ta.value);
            const hidden = document.createElement('input');
            hidden.type = 'hidden'; hidden.name = 'answers'; hidden.value = '';
            for (const [k,v] of Object.entries(parsed)) {
                const h = document.createElement('input'); h.type='hidden'; h.name=`answers[${k}]`; h.value=v; this.appendChild(h);
            }
        } catch (err) {
            e.preventDefault(); alert('JSON jawaban tidak valid: '+err.message);
        }
    }
    ta.removeAttribute('name');
});
</script>
