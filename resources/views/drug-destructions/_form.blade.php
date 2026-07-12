@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Tanggal *</label><input type="date" name="destruction_date" class="form-control" value="{{ old('destruction_date', $destruction?->destruction_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-4"><label class="form-label">Lokasi *</label><input name="location" class="form-control" value="{{ old('location', $destruction?->location) }}" required></div>
    <div class="col-md-4"><label class="form-label">Metode *</label><input name="method" class="form-control" value="{{ old('method', $destruction?->method) }}" placeholder="Insinerasi / dipendam / netralisasi" required></div>
    <div class="col-md-6"><label class="form-label">Apoteker PJ *</label><input name="responsible_pharmacist" class="form-control" value="{{ old('responsible_pharmacist', $destruction?->responsible_pharmacist) }}" required></div>
    <div class="col-md-6"><label class="form-label">Alasan</label><input name="reason" class="form-control" value="{{ old('reason', $destruction?->reason) }}" placeholder="Expired / rusak / ditarik BPOM"></div>
    <div class="col-md-6"><label class="form-label">Saksi 1 (Nama)</label><input name="witness_name_1" class="form-control" value="{{ old('witness_name_1', $destruction?->witness_name_1) }}"></div>
    <div class="col-md-6"><label class="form-label">Saksi 1 (Jabatan)</label><input name="witness_role_1" class="form-control" value="{{ old('witness_role_1', $destruction?->witness_role_1) }}"></div>
    <div class="col-md-6"><label class="form-label">Saksi 2 (Nama)</label><input name="witness_name_2" class="form-control" value="{{ old('witness_name_2', $destruction?->witness_name_2) }}"></div>
    <div class="col-md-6"><label class="form-label">Saksi 2 (Jabatan)</label><input name="witness_role_2" class="form-control" value="{{ old('witness_role_2', $destruction?->witness_role_2) }}"></div>
</div>
<h5>Daftar Obat</h5>
<div class="table-responsive"><table class="table table-bordered" id="ddItems">
<thead class="table-light"><tr><th>Obat *</th><th>Batch</th><th>ED</th><th>Qty *</th><th>Sat.</th><th>Alasan</th><th>—</th></tr></thead>
<tbody>
@php $items = old('items', $destruction?->items?->toArray() ?? [['drug_name'=>'','batch_no'=>'','expired_at'=>'','quantity'=>1,'unit'=>'','reason'=>'']]); @endphp
@foreach($items as $i => $it)
<tr>
    <td><input list="dl" name="items[{{ $i }}][drug_name]" class="form-control form-control-sm" value="{{ $it['drug_name'] ?? '' }}" required></td>
    <td><input name="items[{{ $i }}][batch_no]" class="form-control form-control-sm" value="{{ $it['batch_no'] ?? '' }}"></td>
    <td><input type="date" name="items[{{ $i }}][expired_at]" class="form-control form-control-sm" value="{{ is_string($it['expired_at'] ?? null) ? $it['expired_at'] : (isset($it['expired_at']) ? \Carbon\Carbon::parse($it['expired_at'])->format('Y-m-d') : '') }}"></td>
    <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ $it['quantity'] ?? 1 }}" min="1" required></td>
    <td><input name="items[{{ $i }}][unit]" class="form-control form-control-sm" value="{{ $it['unit'] ?? '' }}"></td>
    <td><input name="items[{{ $i }}][reason]" class="form-control form-control-sm" value="{{ $it['reason'] ?? '' }}"></td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>
</tr>
@endforeach
</tbody></table></div>
<datalist id="dl">@foreach($drugs as $d)<option value="{{ $d->name }}">@endforeach</datalist>
<button type="button" class="btn btn-outline-secondary btn-sm" onclick="addDD()">+ Tambah</button>
<script>
function addDD(){const t=document.querySelector('#ddItems tbody');const i=t.children.length;const r=document.createElement('tr');r.innerHTML=`<td><input list="dl" name="items[${i}][drug_name]" class="form-control form-control-sm" required></td><td><input name="items[${i}][batch_no]" class="form-control form-control-sm"></td><td><input type="date" name="items[${i}][expired_at]" class="form-control form-control-sm"></td><td><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm" value="1" min="1" required></td><td><input name="items[${i}][unit]" class="form-control form-control-sm"></td><td><input name="items[${i}][reason]" class="form-control form-control-sm"></td><td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>`;t.appendChild(r);}
</script>
