@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3 mb-3">
    <div class="col-md-4"><label class="form-label">Pasien *</label><select name="patient_id" class="form-select" required><option value="">— Pilih —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('patient_id',$estimate?->patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Dokter</label><select name="doctor_id" class="form-select"><option value="">— Pilih —</option>@foreach($doctors as $d)<option value="{{ $d->id }}" @selected(old('doctor_id',$estimate?->doctor_id)==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Tanggal *</label><input type="date" name="estimate_date" class="form-control" value="{{ old('estimate_date', $estimate?->estimate_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-12"><label class="form-label">Nama Tindakan *</label><input name="procedure_name" class="form-control" value="{{ old('procedure_name', $estimate?->procedure_name) }}" required></div>
    @if($estimate)<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select">@foreach(['draft','sent','approved','rejected','completed'] as $s)<option value="{{ $s }}" @selected(old('status',$estimate->status)===$s)>{{ ucfirst($s) }}</option>@endforeach</select></div>@endif
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $estimate?->notes) }}</textarea></div>
</div>
<h5>Item Biaya</h5>
<div class="table-responsive"><table class="table table-bordered" id="ceItems">
<thead class="table-light"><tr><th>Deskripsi *</th><th>Qty</th><th>Sat.</th><th>Harga Satuan</th><th>Subtotal</th><th>—</th></tr></thead>
<tbody>
@php $items = old('items', $estimate?->items?->toArray() ?? [['description'=>'','quantity'=>1,'unit'=>'','unit_price'=>0,'subtotal'=>0]]); @endphp
@foreach($items as $i => $it)
<tr>
    <td><input name="items[{{ $i }}][description]" class="form-control form-control-sm" value="{{ $it['description'] ?? '' }}" required></td>
    <td style="width:80px"><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm ce-q" value="{{ $it['quantity'] ?? 1 }}" min="1" required></td>
    <td style="width:80px"><input name="items[{{ $i }}][unit]" class="form-control form-control-sm" value="{{ $it['unit'] ?? '' }}"></td>
    <td style="width:140px"><input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm ce-p" value="{{ $it['unit_price'] ?? 0 }}" min="0" required></td>
    <td style="width:140px"><input type="number" step="0.01" name="items[{{ $i }}][subtotal]" class="form-control form-control-sm ce-s" value="{{ $it['subtotal'] ?? 0 }}" min="0" required readonly></td>
    <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); ceTotal()">×</button></td>
</tr>
@endforeach
</tbody>
<tfoot><tr><td colspan="4" class="text-end"><strong>Total</strong></td><td><strong id="ceTotalCell">0</strong></td><td></td></tr></tfoot>
</table></div>
<button type="button" class="btn btn-outline-secondary btn-sm" onclick="addCE()">+ Tambah</button>
<script>
function ceTotal(){let t=0;document.querySelectorAll('#ceItems tbody tr').forEach(tr=>{const q=parseFloat(tr.querySelector('.ce-q').value)||0;const p=parseFloat(tr.querySelector('.ce-p').value)||0;const s=q*p;tr.querySelector('.ce-s').value=s.toFixed(2);t+=s;});document.getElementById('ceTotalCell').textContent='Rp '+t.toLocaleString('id-ID');}
document.addEventListener('input', e=>{if(e.target.classList.contains('ce-q')||e.target.classList.contains('ce-p')) ceTotal();});
function addCE(){const t=document.querySelector('#ceItems tbody');const i=t.children.length;const r=document.createElement('tr');r.innerHTML=`<td><input name="items[${i}][description]" class="form-control form-control-sm" required></td><td style="width:80px"><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm ce-q" value="1" min="1" required></td><td style="width:80px"><input name="items[${i}][unit]" class="form-control form-control-sm"></td><td style="width:140px"><input type="number" step="0.01" name="items[${i}][unit_price]" class="form-control form-control-sm ce-p" value="0" min="0" required></td><td style="width:140px"><input type="number" step="0.01" name="items[${i}][subtotal]" class="form-control form-control-sm ce-s" value="0" min="0" required readonly></td><td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); ceTotal()">×</button></td>`;t.appendChild(r);ceTotal();}
ceTotal();
</script>
