@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Jenis SP *</label><select name="order_type" class="form-select" required>@foreach($types as $k=>$l)<option value="{{ $k }}" @selected(old('order_type', $order?->order_type)===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Tanggal *</label><input type="date" name="order_date" class="form-control" value="{{ old('order_date', $order?->order_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
    @if($order)<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['draft','sent','received','cancelled'] as $s)<option value="{{ $s }}" @selected(old('status',$order->status)===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>@endif
    <div class="col-md-6"><label class="form-label">Nama Supplier / PBF *</label><input name="supplier_name" class="form-control" value="{{ old('supplier_name', $order?->supplier_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">No. Izin PBF</label><input name="supplier_license_no" class="form-control" value="{{ old('supplier_license_no', $order?->supplier_license_no) }}"></div>
    <div class="col-md-12"><label class="form-label">Alamat Supplier</label><input name="supplier_address" class="form-control" value="{{ old('supplier_address', $order?->supplier_address) }}"></div>
    <div class="col-md-6"><label class="form-label">Apoteker Penanggung Jawab *</label><input name="responsible_pharmacist" class="form-control" value="{{ old('responsible_pharmacist', $order?->responsible_pharmacist) }}" required></div>
    <div class="col-md-6"><label class="form-label">No. SIPA</label><input name="pharmacist_sipa_no" class="form-control" value="{{ old('pharmacist_sipa_no', $order?->pharmacist_sipa_no) }}"></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $order?->notes) }}</textarea></div>
</div>
<h5>Item</h5>
<div class="table-responsive"><table class="table table-bordered" id="spItems">
<thead class="table-light"><tr><th>Nama Obat *</th><th>Bentuk</th><th>Kekuatan</th><th>Jumlah *</th><th>Sat.</th><th>—</th></tr></thead>
<tbody>
@php $items = old('items', $order?->items?->toArray() ?? [['drug_name'=>'','dose_form'=>'','strength'=>'','quantity'=>1,'unit'=>'']]); @endphp
@foreach($items as $i => $it)
<tr>
    <td><input list="drugList" name="items[{{ $i }}][drug_name]" class="form-control form-control-sm" value="{{ $it['drug_name'] ?? '' }}" required></td>
    <td><input name="items[{{ $i }}][dose_form]" class="form-control form-control-sm" value="{{ $it['dose_form'] ?? '' }}" placeholder="tablet"></td>
    <td><input name="items[{{ $i }}][strength]" class="form-control form-control-sm" value="{{ $it['strength'] ?? '' }}" placeholder="500mg"></td>
    <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ $it['quantity'] ?? 1 }}" min="1" required></td>
    <td><input name="items[{{ $i }}][unit]" class="form-control form-control-sm" value="{{ $it['unit'] ?? '' }}" placeholder="box"></td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>
</tr>
@endforeach
</tbody></table></div>
<datalist id="drugList">@foreach($drugs as $d)<option value="{{ $d->name }}">@endforeach</datalist>
<button type="button" class="btn btn-outline-secondary btn-sm" onclick="addSp()">+ Tambah</button>
<script>
function addSp(){const t=document.querySelector('#spItems tbody');const i=t.children.length;const r=document.createElement('tr');r.innerHTML=`<td><input list="drugList" name="items[${i}][drug_name]" class="form-control form-control-sm" required></td><td><input name="items[${i}][dose_form]" class="form-control form-control-sm" placeholder="tablet"></td><td><input name="items[${i}][strength]" class="form-control form-control-sm" placeholder="500mg"></td><td><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm" value="1" min="1" required></td><td><input name="items[${i}][unit]" class="form-control form-control-sm" placeholder="box"></td><td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove()">×</button></td>`;t.appendChild(r);}
</script>
