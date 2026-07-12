@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Ruangan *</label><select name="room_id" class="form-select" required><option value="">— Pilih —</option>@foreach($rooms as $r)<option value="{{ $r->id }}" @selected(old('room_id', $bed?->room_id)==$r->id)>{{ $r->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Kode Bed *</label><input name="bed_code" class="form-control" value="{{ old('bed_code', $bed?->bed_code) }}" required maxlength="50"></div>
    <div class="col-md-4"><label class="form-label">Label</label><input name="label" class="form-control" value="{{ old('label', $bed?->label) }}" placeholder="Bed 1 / Window side"></div>
    <div class="col-md-4"><label class="form-label">Status *</label><select name="status" class="form-select">@foreach($statuses as $k=>$l)<option value="{{ $k }}" @selected(old('status', $bed?->status ?? 'available')===$k)>{{ $l }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Pasien Saat Ini</label><select name="current_patient_id" class="form-select"><option value="">— Kosong —</option>@foreach($patients as $p)<option value="{{ $p->id }}" @selected(old('current_patient_id', $bed?->current_patient_id)==$p->id)>{{ $p->name }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Mulai Ditempati</label><input type="datetime-local" name="occupied_since" class="form-control" value="{{ old('occupied_since', $bed?->occupied_since?->format('Y-m-d\TH:i')) }}"></div>
    <div class="col-md-12"><label class="form-label">Catatan</label><textarea name="notes" rows="2" class="form-control">{{ old('notes', $bed?->notes) }}</textarea></div>
</div>
