<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_register_with_valid_data(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivan@example.com',
            'password' => '12345678',
            'phone' => '+79990000001',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'name' => 'Иванов Иван Иванович',
            'email' => 'ivan@example.com',
            'phone' => '+79990000001',
            'role' => 'visitor',
        ]);
    }

    public function test_registration_validates_email_and_phone(): void
    {
        $response = $this->from(route('register'))->post(route('register.post'), [
            'name' => 'Петров Петр Петрович',
            'email' => 'wrong-email',
            'password' => '12345678',
            'phone' => 'abc',
        ]);

        $response
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors(['email', 'phone']);

        $this->assertDatabaseMissing('users', [
            'name' => 'Петров Петр Петрович',
        ]);
    }

    public function test_leader_is_redirected_to_cabinet_after_login(): void
    {
        User::create([
            'name' => 'Ведущий Тестовый',
            'email' => 'leader@example.com',
            'phone' => '+79990000002',
            'password' => '12345678',
            'role' => 'leader',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'leader@example.com',
            'password' => '12345678',
        ]);

        $response->assertRedirect(route('cabinet'));
        $this->assertAuthenticated();
    }
}
