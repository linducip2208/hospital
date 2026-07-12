@extends('layouts.admin')

@section('title', 'Integrasi Satu Sehat (SATUSEHAT)')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-header mb-3">
    <h1 class="h2">Integrasi Satu Sehat (SATUSEHAT)</h1>
    <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

    <div class="row g-3">
    <div class="col-md-8">
        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i> Tentang Satu Sehat
            </div>
            <div class="card-body">
                <p>Satu Sehat (SATUSEHAT) adalah platform pertukaran data kesehatan nasional yang dikelola oleh Kementerian Kesehatan Republik Indonesia. Platform ini menghubungkan seluruh fasilitas pelayanan kesehatan di Indonesia untuk integrasi data rekam medis elektronik (RME).</p>
                <p class="mb-0">Dengan mengaktifkan integrasi ini, aplikasi dapat mengirim dan menerima data kesehatan melalui API SATUSEHAT sesuai standar FHIR (Fast Healthcare Interoperability Resources).</p>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header">
                <i class="bi bi-sliders me-2"></i> Konfigurasi API
            </div>
            <div class="card-body">
                <form action="{{ route('settings.satusehat.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Base URL</label>
                            <input type="text" name="satusehat_base_url" class="form-control @error('satusehat_base_url') is-invalid @enderror" value="{{ old('satusehat_base_url', $settings['satusehat_base_url'] ?? '') }}" placeholder="https://api.satusehat.kemkes.go.id">
                            @error('satusehat_base_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client ID</label>
                            <input type="password" name="satusehat_client_id" class="form-control @error('satusehat_client_id') is-invalid @enderror" value="{{ old('satusehat_client_id', $settings['satusehat_client_id'] ?? '') }}" id="satusehatClientId" placeholder="Biarkan kosong jika tidak diubah">
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="showClientId">
                                <label class="form-check-label small" for="showClientId">Tampilkan</label>
                            </div>
                            @error('satusehat_client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client Secret</label>
                            <input type="password" name="satusehat_client_secret" class="form-control @error('satusehat_client_secret') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            @error('satusehat_client_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Organization ID</label>
                            <input type="text" name="satusehat_organization_id" class="form-control @error('satusehat_organization_id') is-invalid @enderror" value="{{ old('satusehat_organization_id', $settings['satusehat_organization_id'] ?? '') }}">
                            @error('satusehat_organization_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="satusehat_is_enabled" class="form-check-input" value="1" id="satusehatEnabled" @checked(old('satusehat_is_enabled', $settings['satusehat_is_enabled'] ?? false))>
                                <label class="form-check-label" for="satusehatEnabled">Aktifkan Integrasi</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Konfigurasi</button>
                        <button type="button" class="btn btn-outline-info" id="testConnectionBtn" onclick="testConnection()">
                            <i class="bi bi-plug"></i> <span id="testConnText">Tes Koneksi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header">
                <i class="bi bi-arrow-repeat me-2"></i> Konfigurasi Webhook
            </div>
            <div class="card-body">
                <form action="{{ route('settings.satusehat.update') }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="satusehat_base_url" value="{{ old('satusehat_base_url', $settings['satusehat_base_url'] ?? '') }}">
                    <input type="hidden" name="satusehat_client_id" value="{{ old('satusehat_client_id', $settings['satusehat_client_id'] ?? '') }}">
                    <input type="hidden" name="satusehat_client_secret">
                    <input type="hidden" name="satusehat_organization_id" value="{{ old('satusehat_organization_id', $settings['satusehat_organization_id'] ?? '') }}">
                    <input type="hidden" name="satusehat_is_enabled" value="{{ $settings['satusehat_is_enabled'] ?? '0' }}">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Webhook URL <small class="text-muted">(URL aplikasi ini yang akan dipanggil SATUSEHAT)</small></label>
                            <input type="text" name="satusehat_webhook_url" class="form-control @error('satusehat_webhook_url') is-invalid @enderror" value="{{ old('satusehat_webhook_url', $settings['satusehat_webhook_url'] ?? url('/api/satusehat/webhook')) }}" placeholder="{{ url('/api/satusehat/webhook') }}">
                            @error('satusehat_webhook_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Default: {{ url('/api/satusehat/webhook') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Webhook Secret</label>
                            <input type="password" name="satusehat_webhook_secret" class="form-control @error('satusehat_webhook_secret') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            @error('satusehat_webhook_secret') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Digunakan untuk verifikasi signature webhook dari SATUSEHAT</small>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="satusehat_webhook_enabled" class="form-check-input" value="1" id="satusehatWebhookEnabled" @checked(old('satusehat_webhook_enabled', $settings['satusehat_webhook_enabled'] ?? false))>
                                <label class="form-check-label" for="satusehatWebhookEnabled">Aktifkan Webhook</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Webhook</button>
                        <button type="button" class="btn btn-outline-info" id="testWebhookBtn" onclick="testWebhook()">
                            <i class="bi bi-send"></i> <span id="testWebhookText">Tes Webhook</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <i class="bi bi-plug me-2"></i> Status Koneksi
            </div>
            <div class="card-body text-center">
                @if($settings['satusehat_is_enabled'] ?? false)
                    <i class="bi bi-check-circle text-success display-3"></i>
                    <h5 class="mt-2 text-success">Terhubung</h5>
                @else
                    <i class="bi bi-x-circle text-secondary display-3"></i>
                    <h5 class="mt-2 text-muted">Belum terhubung</h5>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-header">
                <i class="bi bi-question-circle me-2"></i> Perlu Bantuan?
            </div>
            <div class="card-body">
                <p class="small text-muted mb-2">Untuk mendapatkan kredensial API SATUSEHAT:</p>
                <ol class="small text-muted mb-0 ps-3">
                    <li>Daftarkan faskes ke SATUSEHAT melalui portal resmi</li>
                    <li>Ajukan permohonan akses API</li>
                    <li>Gunakan Client ID & Secret yang diberikan</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('showClientId').addEventListener('change', function() {
    var el = document.getElementById('satusehatClientId');
    el.type = this.checked ? 'text' : 'password';
});

function testConnection() {
    var btn = document.getElementById('testConnectionBtn');
    var text = document.getElementById('testConnText');
    btn.disabled = true;
    text.textContent = 'Menghubungi...';

    submitHiddenForm('{{ route('settings.satusehat.test') }}');
}

function testWebhook() {
    var btn = document.getElementById('testWebhookBtn');
    var text = document.getElementById('testWebhookText');
    btn.disabled = true;
    text.textContent = 'Mengirim...';

    submitHiddenForm('{{ route('settings.satusehat.webhook.test') }}');
}

function submitHiddenForm(actionUrl) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = actionUrl;
    form.style.display = 'none';

    var csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush
