<?php

namespace Tests\Feature;

use App\Models\MemberProfile;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileMemberDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_mobile_dashboard(): void
    {
        $response = $this->get('/m/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_member_can_access_mobile_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Pratama',
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'member_code' => 'MEM-123456',
        ]);

        $response = $this->actingAs($member)->get('/m/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali');
        $response->assertSee('Budi Pratama');
        $response->assertSee('Buka Kartu Member Digital');
        $response->assertSee('Sisa Kuota PT');
        $response->assertSee('Beranda');
        $response->assertSee('Sesi PT');
    }

    public function test_member_can_access_mobile_digital_card(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Pratama',
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'member_code' => 'MEM-123456',
            'qr_token' => 'test-qr-token-uuid-1234',
        ]);

        $response = $this->actingAs($member)->get('/m/card');

        $response->assertStatus(200);
        $response->assertSee('Kartu Akses Gym');
        $response->assertSee('Budi Pratama');
        $response->assertSee('MEM-123456');
    }

    public function test_member_can_access_mobile_pt_booking_wizard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Dimas',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Hypertrophy & Conditioning',
        ]);

        $response = $this->actingAs($member)->get('/m/pt-sessions/booking');

        $response->assertStatus(200);
        $response->assertSee('Booking Sesi PT');
        $response->assertSee('1. Pilih Trainer');
        $response->assertSee('2. Waktu');
        $response->assertSee('3. Konfirmasi');
        $response->assertSee('Coach Dimas');
    }

    public function test_member_can_access_mobile_pt_sessions_index(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get('/m/pt-sessions');

        $response->assertStatus(200);
        $response->assertSee('Sesi Latihan PT');
        $response->assertSee('+ Booking');
    }

    public function test_trainer_can_access_mobile_trainer_dashboard(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'name' => 'Coach Sarah',
        ]);

        TrainerProfile::create([
            'user_id' => $trainer->id,
            'specialization' => 'Fat Loss & HIIT',
        ]);

        $response = $this->actingAs($trainer)->get('/m/trainer/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Selamat Bertugas');
        $response->assertSee('Coach Sarah');
        $response->assertSee('Sesi Hari Ini');
        $response->assertSee('Butuh Respon');
    }

    public function test_non_member_cannot_access_mobile_dashboard(): void
    {
        $trainer = User::factory()->create([
            'role' => 'trainer',
        ]);

        $response = $this->actingAs($trainer)->get('/m/dashboard');

        $response->assertStatus(403);
    }
}
