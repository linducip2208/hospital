@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Pasien *</label>
        <select name="patient_id" class="form-select" required>
            <option value="">— Pilih —</option>
            @foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id', $prescription?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Dokter Penulis</label>
        <select name="doctor_id" class="form-select">
            <option value="">— Pilih —</option>
            @foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id', $prescription?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal *</label>
        <input type="date" name="prescribed_at" class="form-control" value="{{ old('prescribed_at', $prescription?->prescribed_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Iter</label>
        <select name="is_iter" class="form-select">
            <option value="0" @selected(!old('is_iter', $prescription?->is_iter))>Tidak</option>
            <option value="1" @selected(old('is_iter', $prescription?->is_iter))>Ya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jumlah Iter</label>
        <input type="number" name="iter_count" min="0" max="10" class="form-control" value="{{ old('iter_count', $prescription?->iter_count ?? 0) }}">
    </div>
    @if($prescription)
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['draft','issued','dispensed','cancelled'] as $s)<option value="{{ $s }}" @selected(old('status', $prescription->status)===$s)>{{ ucfirst($s) }}</option>@endforeach
        </select>
    </div>
    @endif
    <div class="col-md-12">
        <label class="form-label">Catatan</label>
        <textarea name="notes" rows="2" class="form-control">{{ old('notes', $prescription?->notes) }}</textarea>
    </div>
</div>

<h5>Item Obat</h5>
<div class="table-responsive">
<table class="table table-bordered" id="rxItems">
    <thead class="table-light">
        <tr>
            <th style="min-width:200px">Nama Obat *</th>
            <th>Dosis</th>
            <th>Frek.</th>
            <th>Rute</th>
            <th>Lama</th>
            <th>Qty *</th>
            <th>Sat.</th>
            <th>Aturan Pakai</th>
            <th>HA/Racikan</th>
            <th>—</th>
        </tr>
    </thead>
    <tbody>
        @php $items = old('items', $prescription?->items?->toArray() ?? [['drug_name'=>'','dose'=>'','frequency'=>'','route'=>'','duration'=>'','quantity'=>1,'unit'=>'','instructions'=>'']]); @endphp
        @foreach($items as $i => $item)
        <tr>
            <td><input list="drugList" name="items[{{ $i }}][drug_name]" class="form-control form-control-sm" value="{{ $item['drug_name'] ?? '' }}" required></td>
            <td><input name="items[{{ $i }}][dose]" class="form-control form-control-sm" value="{{ $item['dose'] ?? '' }}"></td>
            <td><input name="items[{{ $i }}][frequency]" class="form-control form-control-sm" value="{{ $item['frequency'] ?? '' }}" placeholder="3x1"></td>
            <td><input name="items[{{ $i }}][route]" class="form-control form-control-sm" value="{{ $item['route'] ?? '' }}" placeholder="oral"></td>
            <td><input name="items[{{ $i }}][duration]" class="form-control form-control-sm" value="{{ $item['duration'] ?? '' }}" placeholder="5 hari"></td>
            <td style="width:80px"><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ $item['quantity'] ?? 1 }}" min="1" required></td>
            <td style="width:80px"><input name="items[{{ $i }}][unit]" class="form-control form-control-sm" value="{{ $item['unit'] ?? '' }}" placeholder="tab"></td>
            <td><input name="items[{{ $i }}][instructions]" class="form-control form-control-sm" value="{{ $item['instructions'] ?? '' }}" placeholder="setelah makan"></td>
            <td>
                <label class="small d-block"><input type="checkbox" name="items[{{ $i }}][is_compounded]" value="1" {{ !empty($item['is_compounded']) ? 'checked' : '' }}> Racik</label>
                <label class="small d-block"><input type="checkbox" name="items[{{ $i }}][is_high_alert]" value="1" {{ !empty($item['is_high_alert']) ? 'checked' : '' }}> HA</label>
            </td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
<datalist id="drugList">
    @foreach($drugs as $d)<option value="{{ $d->name }}">@endforeach
</datalist>
<button type="button" class="btn btn-outline-secondary btn-sm" onclick="addRxRow()">+ Tambah Obat</button>
<script>
function addRxRow() {
    const tbody = document.querySelector('#rxItems tbody');
    const i = tbody.children.length;
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input list="drugList" name="items[${i}][drug_name]" class="form-control form-control-sm" required></td>
        <td><input name="items[${i}][dose]" class="form-control form-control-sm"></td>
        <td><input name="items[${i}][frequency]" class="form-control form-control-sm" placeholder="3x1"></td>
        <td><input name="items[${i}][route]" class="form-control form-control-sm" placeholder="oral"></td>
        <td><input name="items[${i}][duration]" class="form-control form-control-sm" placeholder="5 hari"></td>
        <td style="width:80px"><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm" value="1" min="1" required></td>
        <td style="width:80px"><input name="items[${i}][unit]" class="form-control form-control-sm" placeholder="tab"></td>
        <td><input name="items[${i}][instructions]" class="form-control form-control-sm" placeholder="setelah makan"></td>
        <td>
            <label class="small d-block"><input type="checkbox" name="items[${i}][is_compounded]" value="1"> Racik</label>
            <label class="small d-block"><input type="checkbox" name="items[${i}][is_high_alert]" value="1"> HA</label>
        </td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>`;
    tbody.appendChild(row);
}
</script>
