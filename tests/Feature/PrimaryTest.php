<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrimaryTest extends TestCase
{
    public function testIndexPageIsOk(): void
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertSeeText('Zdrava');
    }

    public function testLoginPageIsOk(): void
    {
        $response = $this->get(route('auth.login'));

        $response->assertStatus(200);
        $response->assertSeeText(__('Remember me'));
    }

    public function testRegisterPageIsOk(): void
    {
        $response = $this->get(route('auth.register'));

        $response->assertStatus(200);
        $response->assertSeeText(__('Subscribe to our newsletter'));
    }
}
