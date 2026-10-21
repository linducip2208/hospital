<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Services\SatuSehatClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SatuSehatUrlTest extends TestCase
{
    use RefreshDatabase;

    private function setStg(string $base, string $auth): void
    {
        foreach (['satusehat_env' => 'stg', 'satusehat_stg_base_url' => $base, 'satusehat_stg_auth_base_url' => $auth] as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v, 'group' => 'satu_sehat', 'type' => 'text']);
        }
    }

    public function test_url_pendek_host_saja(): void
    {
        $this->setStg('https://api-satusehat-stg.dto.kemkes.go.id', 'https://api-satusehat-stg.dto.kemkes.go.id');
        $this->assertSame(
            'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken',
            (new SatuSehatClient)->getTokenUrl()
        );
    }

    public function test_url_lengkap_ala_aplikasi_windows(): void
    {
        $this->setStg(
            'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1',
            'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken?grant_type=client_credentials'
        );
        // Path + query dibuang, endpoint dirakit ulang dengan benar (tidak ganda).
        $this->assertSame(
            'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1/accesstoken',
            (new SatuSehatClient)->getTokenUrl()
        );
    }
}
