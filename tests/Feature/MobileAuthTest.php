<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_mobile_login_page(): void
    {
        $response = $this->get('/m/login');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee('Kembali');
        $response->assertSee('Member');
        $response->assertSee('Personal Trainer');
        $response->assertSee('Email Pengguna');
        $response->assertSee('Kata Sandi');
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('Atau Masuk Dengan');
        $response->assertSee('Google');
        $response->assertSee('Apple');
        $response->assertSee('Data Anda terlindungi dengan enkripsi end-to-end 256-bit');
    }

    public function test_guest_can_view_mobile_register_page(): void
    {
        $response = $this->get('/m/register');

        $response->assertStatus(200);
        $response->assertSee('Bergabung dengan FitCore');
        $response->assertSee('Nama Lengkap');
        $response->assertSee('Alamat Email');
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('+62');
        $response->assertSee('Daftar Member Sekarang');
        $response->assertSee('Atau Daftar Dengan');
        $response->assertSee('Data Anda terlindungi dengan enkripsi end-to-end 256-bit');
    }

    public function test_authenticated_user_cannot_view_mobile_login_or_register(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $responseLogin = $this->actingAs($member)->get('/m/login');
        $responseLogin->assertRedirect('/dashboard');

        $responseRegister = $this->actingAs($member)->get('/m/register');
        $responseRegister->assertRedirect('/dashboard');
    }

    public function test_member_can_authenticate_using_mobile_login(): void
    {
        $user = User::factory()->create([
            'email' => 'member.mobile@fitcore.test',
            'role' => 'member',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'member.mobile@fitcore.test',
            'password' => 'password123',
            'role' => 'member',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/member/dashboard');
    }

    public function test_trainer_can_authenticate_using_mobile_login(): void
    {
        $trainer = User::factory()->create([
            'email' => 'trainer.mobile@fitcore.test',
            'role' => 'trainer',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'trainer.mobile@fitcore.test',
            'password' => 'password123',
            'role' => 'trainer',
        ]);

        $this->assertAuthenticatedAs($trainer);
        $response->assertRedirect('/trainer/dashboard');
    }
}
