<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiRoutesTest extends TestCase
{
    public function test_protected_api_routes_require_authentication(): void
    {
        $this->getJson('/api/me')->assertUnauthorized();
    }
}
