@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Alat Kesehatan <span class="text-danger">*</span></label>
                <select name="asset_id" class="form-select" required>
                    <option value="">— Pilih Alat —</option>
                    @foreach($assets as $a)
                        <option value="{{ $a->id }}" @selected(old('asset_id', $calibration->asset_id ?? '')==$a->id)>{{ $a->name }} ({{ $a->asset_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Tgl Kalibrasi <span class="text-danger">*</span></label><input type="date" name="calibration_date" class="form-control" value="{{ old('calibration_date', isset($calibration) ? $calibration->calibration_date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required></div>
            <div class="col-md-3"><label class="form-label">Jatuh Tempo Berikutnya <span class="text-danger">*</span></label><input type="date" name="next_due_date" class="form-control" value="{{ old('next_due_date', isset($calibration) ? $calibration->next_due_date?->format('Y-m-d') : now()->addYear()->format('Y-m-d')) }}" required></div>
            <div class="col-md-4"><label class="form-label">Dilakukan Oleh</label><input type="text" name="performed_by" class="form-control" value="{{ old('performed_by', $calibration->performed_by ?? '') }}" placeholder="Teknisi / lembaga"></div>
            <div class="col-md-4"><label class="form-label">No. Sertifikat</label><input type="text" name="certificate_no" class="form-control" value="{{ old('certificate_no', $calibration->certificate_no ?? '') }}"></div>
            <div class="col-md-2"><label class="form-label">Hasil</label>
                <select name="result" class="form-select">
                    @foreach(['pass'=>'Lulus','pass_with_note'=>'Lulus (Catatan)','fail'=>'Gagal'] as $k=>$v)
                        <option value="{{ $k }}" @selected(old('result',$calibration->result??'pass')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['completed'=>'Selesai','scheduled'=>'Terjadwal','overdue'=>'Overdue'] as $k=>$v)
                        <option value="{{ $k }}" @selected(old('status',$calibration->status??'completed')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $calibration->notes ?? '') }}</textarea></div>
        </div>
        <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button><a href="{{ route('equipment-calibrations.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </div>
</div>
