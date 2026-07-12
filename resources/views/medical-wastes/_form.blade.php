@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Jenis Limbah <span class="text-danger">*</span></label>
                <select name="waste_type" class="form-select" required>
                    @foreach($typeLabels as $k=>$v)<option value="{{ $k }}" @selected(old('waste_type',$waste->waste_type??'infectious')===$k)>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Berat (kg) <span class="text-danger">*</span></label><input type="number" step="0.01" name="weight_kg" class="form-control" value="{{ old('weight_kg', $waste->weight_kg ?? '') }}" required></div>
            <div class="col-md-4"><label class="form-label">Unit / Departemen</label>
                <select name="department_id" class="form-select">
                    <option value="">— Pilih —</option>
                    @foreach($departments as $d)<option value="{{ $d->id }}" @selected(old('department_id',$waste->department_id??'')==$d->id)>{{ $d->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Tgl Pengumpulan <span class="text-danger">*</span></label><input type="date" name="collection_date" class="form-control" value="{{ old('collection_date', isset($waste) ? $waste->collection_date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Tgl Pembuangan</label><input type="date" name="disposal_date" class="form-control" value="{{ old('disposal_date', isset($waste) ? $waste->disposal_date?->format('Y-m-d') : '') }}"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['stored'=>'Tersimpan','transported'=>'Diangkut','disposed'=>'Dibuang'] as $k=>$v)<option value="{{ $k }}" @selected(old('status',$waste->status??'stored')===$k)>{{ $v }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Ditangani Oleh</label><input type="text" name="handled_by" class="form-control" value="{{ old('handled_by', $waste->handled_by ?? '') }}"></div>
            <div class="col-md-6"><label class="form-label">Pengangkut (Transporter)</label><input type="text" name="transporter" class="form-control" value="{{ old('transporter', $waste->transporter ?? '') }}" placeholder="Pihak pengangkut berizin"></div>
            <div class="col-md-6"><label class="form-label">Vendor Pengelola</label>
                <select name="vendor_id" class="form-select">
                    <option value="">— Pilih —</option>
                    @foreach($vendors as $v)<option value="{{ $v->id }}" @selected(old('vendor_id',$waste->vendor_id??'')==$v->id)>{{ $v->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $waste->notes ?? '') }}</textarea></div>
        </div>
        <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button><a href="{{ route('medical-wastes.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </div>
</div>
