@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $odontogram?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Dokter Gigi</label><select name="doctor_id" class="form-select"><option value="">— Pilih —</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $odontogram?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Tgl Periksa *</label><input type="date" name="exam_date" class="form-control" value="{{ old('exam_date', $odontogram?->exam_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
</div>

@php
    $stateLegend = ['' => 'Sehat', 'CAR'=>'Caries','AMF'=>'Tambal Amalgam','COF'=>'Tambal Komposit','RCT'=>'PSA','CRN'=>'Crown','EXT'=>'Cabut','MIS'=>'Hilang','IMP'=>'Implant'];
    $teethState = old('teeth_state', $odontogram?->teeth_state ?? []);
    $upperRight = [18,17,16,15,14,13,12,11];
    $upperLeft = [21,22,23,24,25,26,27,28];
    $lowerLeft = [38,37,36,35,34,33,32,31];
    $lowerRight = [41,42,43,44,45,46,47,48];
@endphp

<h5>Odontogram Permanen (FDI)</h5>
<div class="alert alert-info small">Klik kotak gigi untuk pilih kondisi: <strong>kosong</strong>=sehat, <strong>CAR</strong>=karies, <strong>AMF/COF</strong>=tambal, <strong>RCT</strong>=PSA, <strong>CRN</strong>=crown, <strong>EXT</strong>=cabut, <strong>MIS</strong>=hilang, <strong>IMP</strong>=implant.</div>
<style>
.odo-row { display:flex; gap:2px; justify-content:center; margin-bottom:4px; }
.tooth { width:50px; height:60px; border:2px solid #333; display:flex; flex-direction:column; align-items:center; justify-content:center; font-size:10pt; cursor:pointer; background:#fff; user-select:none; }
.tooth .num { font-size:8pt; color:#777; }
.tooth .st { font-size:9pt; font-weight:bold; }
.tooth.has-state { background:#fef3c7; }
.tooth[data-state="EXT"], .tooth[data-state="MIS"] { background:#fee2e2; }
.tooth[data-state="CAR"] { background:#fed7aa; }
.midline { border-left:2px dashed #999; height:60px; margin:0 4px; }
</style>

<div style="background:#f9fafb; padding:14px; border-radius:6px;">
    <div class="odo-row">
        @foreach($upperRight as $n)
            @php $st = $teethState[$n] ?? ''; @endphp
            <div class="tooth {{ $st ? 'has-state' : '' }}" data-tooth="{{ $n }}" data-state="{{ $st }}" onclick="cycleTooth(this)">
                <div class="num">{{ $n }}</div><div class="st">{{ $st }}</div>
                <input type="hidden" name="teeth_state[{{ $n }}]" value="{{ $st }}">
            </div>
        @endforeach
        <div class="midline"></div>
        @foreach($upperLeft as $n)
            @php $st = $teethState[$n] ?? ''; @endphp
            <div class="tooth {{ $st ? 'has-state' : '' }}" data-tooth="{{ $n }}" data-state="{{ $st }}" onclick="cycleTooth(this)">
                <div class="num">{{ $n }}</div><div class="st">{{ $st }}</div>
                <input type="hidden" name="teeth_state[{{ $n }}]" value="{{ $st }}">
            </div>
        @endforeach
    </div>
    <div class="odo-row">
        @foreach($lowerRight as $n)
            @php $st = $teethState[$n] ?? ''; @endphp
            <div class="tooth {{ $st ? 'has-state' : '' }}" data-tooth="{{ $n }}" data-state="{{ $st }}" onclick="cycleTooth(this)">
                <div class="num">{{ $n }}</div><div class="st">{{ $st }}</div>
                <input type="hidden" name="teeth_state[{{ $n }}]" value="{{ $st }}">
            </div>
        @endforeach
        <div class="midline"></div>
        @foreach($lowerLeft as $n)
            @php $st = $teethState[$n] ?? ''; @endphp
            <div class="tooth {{ $st ? 'has-state' : '' }}" data-tooth="{{ $n }}" data-state="{{ $st }}" onclick="cycleTooth(this)">
                <div class="num">{{ $n }}</div><div class="st">{{ $st }}</div>
                <input type="hidden" name="teeth_state[{{ $n }}]" value="{{ $st }}">
            </div>
        @endforeach
    </div>
</div>

<div class="row g-3 mt-2">
    <div class="col-md-12"><label class="form-label">Temuan Umum</label><textarea name="general_findings" rows="2" class="form-control">{{ old('general_findings', $odontogram?->general_findings) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Rencana Perawatan</label><textarea name="treatment_plan" rows="2" class="form-control">{{ old('treatment_plan', $odontogram?->treatment_plan) }}</textarea></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $odontogram?->notes) }}</textarea></div>
</div>

<script>
const states = ['', 'CAR', 'AMF', 'COF', 'RCT', 'CRN', 'EXT', 'MIS', 'IMP'];
function cycleTooth(el) {
    const cur = el.dataset.state || '';
    const idx = states.indexOf(cur);
    const next = states[(idx + 1) % states.length];
    el.dataset.state = next;
    el.querySelector('.st').textContent = next;
    el.querySelector('input[type=hidden]').value = next;
    el.classList.toggle('has-state', !!next);
}
</script>
