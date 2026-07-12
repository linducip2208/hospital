@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<div class="card shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Nama Vendor <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name', $vendor->name ?? '') }}" required></div>
            <div class="col-md-6"><label class="form-label">Kode <span class="text-danger">*</span></label><input type="text" name="code" class="form-control" value="{{ old('code', $vendor->code ?? 'VND-'.strtoupper(\Illuminate\Support\Str::random(4))) }}" required></div>
            <div class="col-md-4"><label class="form-label">Kategori</label>
                <select name="category" class="form-select">
                    @foreach(['farmasi'=>'Farmasi','alkes'=>'Alat Kesehatan','umum'=>'Umum','limbah'=>'Pengelola Limbah'] as $k=>$v)
                        <option value="{{ $k }}" @selected(old('category',$vendor->category??'')===$k)>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="form-label">Kontak Person</label><input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $vendor->contact_person ?? '') }}"></div>
            <div class="col-md-4"><label class="form-label">Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone ?? '') }}"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $vendor->email ?? '') }}"></div>
            <div class="col-md-6"><label class="form-label">Alamat</label><input type="text" name="address" class="form-control" value="{{ old('address', $vendor->address ?? '') }}"></div>
            <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $vendor->notes ?? '') }}</textarea></div>
            <div class="col-12"><div class="form-check form-switch"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="va" @checked(old('is_active', $vendor->is_active ?? true))><label class="form-check-label" for="va">Aktif</label></div></div>
        </div>
        <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button><a href="{{ route('vendors.index') }}" class="btn btn-outline-secondary">Batal</a></div>
    </div>
</div>
