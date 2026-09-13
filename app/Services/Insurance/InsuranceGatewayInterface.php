<?php

namespace App\Services\Insurance;

use App\Models\Patient;

interface InsuranceGatewayInterface
{
    public function checkEligibility(Patient $patient): array;
    public function mode(): string;
}
