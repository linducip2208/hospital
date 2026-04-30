<?php

namespace Tests;

use App\Http\Middleware\RequirePair;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Disable the RequirePair middleware during tests so that
        // web routes are accessible without a valid license pairing.
        $this->withoutMiddleware(RequirePair::class);
    }
}
