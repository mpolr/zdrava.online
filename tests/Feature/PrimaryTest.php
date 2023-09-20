<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrimaryTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testIndexPageIsOk(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertSeeText('Zdrava');
    }
}
