<?php

namespace Tests\Feature;

use App\Models\Bank;
use App\Models\MemberProfile;
use App\Models\MemberPtQuota;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\PtSession;
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

    public function test_member_can_access_mobile_packages_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Pratama',
        ]);

        MembershipPackage::create([
            'name' => 'Paket Gold FitCore',
            'type' => 'membership',
            'duration_days' => 30,
            'price' => 350000,
            'facilities' => ['Akses Penuh Area Gym', 'Loker Privat'],
            'pt_session_count' => 2,
            'status' => 'active',
            'is_active' => true,
            'tier' => 'Gold',
            'badge' => 'Best Value',
        ]);

        $response = $this->actingAs($member)->get('/m/packages');

        $response->assertStatus(200);
        $response->assertSee('Pilih Paket Keanggotaan');
        $response->assertSee('Paket Gold FitCore');
        $response->assertSee('350.000');
        $response->assertSee('Best Value');
        $response->assertSee('2 Sesi');
        $response->assertSee('Paket');

        // Test route mobile.packages.index also works
        $catalogResponse = $this->actingAs($member)->get(route('mobile.packages.index'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Paket Gold FitCore');
    }

    public function test_member_can_access_mobile_package_checkout(): void
    {
        $member = User::factory()->completeProfile()->create([
            'role' => 'member',
            'name' => 'Budi Pratama',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Paket Platinum FitCore',
            'type' => 'membership',
            'duration_days' => 90,
            'price' => 900000,
            'status' => 'active',
            'is_active' => true,
        ]);

        Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Indonesia',
            'status' => 'active',
        ]);

        $response = $this->actingAs($member)->get(route('mobile.member.packages.checkout', $package->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Pembayaran');
        $response->assertSee('Paket Platinum FitCore');
        $response->assertSee('900.000');
        $response->assertSee('BCA');
        $response->assertSee('1234567890');
        $response->assertSee('Kirim Bukti Pembayaran');
    }

    public function test_member_can_access_mobile_payment_status(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Paket Silver FitCore',
            'type' => 'membership',
            'duration_days' => 30,
            'price' => 250000,
            'status' => 'active',
            'is_active' => true,
        ]);

        $bank = Bank::create([
            'name' => 'Mandiri',
            'account_number' => '9876543210',
            'account_holder' => 'FitCore Indonesia',
            'status' => 'active',
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-TEST1234',
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 250500,
            'bank_id' => $bank->id,
            'bank_sender' => 'Budi Pratama',
            'bank_destination' => 'Mandiri (9876543210)',
            'proof_image_path' => 'payments/test.jpg',
            'proof_image_url' => '/storage/payments/test.jpg',
            'transfer_date' => now(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($member)->get(route('mobile.member.packages.payment-status', $payment->id));

        $response->assertStatus(200);
        $response->assertSee('Bukti Pembayaran Diterima');
        $response->assertSee('INV-TEST1234');
        $response->assertSee('Paket Silver FitCore');
        $response->assertSee('Menunggu Verifikasi Admin');
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
        $response->assertSee('Semua');
        $response->assertSee('Menunggu');
        $response->assertSee('Terkonfirmasi');
        $response->assertSee('Dibatalkan');
        // Pastikan active state bottom navbar berada pada tab Sesi PT
        $response->assertSee('currentNavTab: \'pt-sessions\'', false);
    }

    public function test_member_can_submit_pt_booking_from_mobile_wizard(): void
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
            'specialization' => 'Hypertrophy',
        ]);

        MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 0,
            'remaining_sessions' => 10,
            'end_date' => now()->addMonth(),
        ]);

        $targetDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($member)->post(route('pt-sessions.store'), [
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '08:00',
            'workout_focus' => 'Chest & Triceps Hypertrophy',
        ]);

        $response->assertRedirect(route('pt-sessions.index'));
        $this->assertDatabaseHas('pt_sessions', [
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => $targetDate,
            'start_time' => '08:00:00',
        ]);
    }

    public function test_member_can_view_mobile_pt_session_detail(): void
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
            'specialization' => 'Hypertrophy Specialist',
            'rating' => 4.9,
        ]);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member->id,
            'total_sessions' => 10,
            'used_sessions' => 1,
            'remaining_sessions' => 9,
            'end_date' => now()->addMonth(),
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($member)->get(route('mobile.member.pt-sessions.show', $session->id));

        $response->assertStatus(200);
        $response->assertSee('Detail Sesi PT');
        $response->assertSee('Coach Dimas');
        $response->assertSee('Hypertrophy Specialist');
        $response->assertSee('09:00 - 10:00 WIB');
        $response->assertSee('Sesi Terkonfirmasi');
    }

    public function test_other_member_cannot_view_another_members_session(): void
    {
        $member1 = User::factory()->create(['role' => 'member']);
        $member2 = User::factory()->create(['role' => 'member']);
        $trainer = User::factory()->create(['role' => 'trainer']);

        $quota = MemberPtQuota::factory()->create([
            'member_id' => $member1->id,
        ]);

        $session = PtSession::create([
            'member_pt_quota_id' => $quota->id,
            'member_id' => $member1->id,
            'trainer_id' => $trainer->id,
            'session_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00',
            'end_time' => '10:00',
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($member2)->get(route('mobile.member.pt-sessions.show', $session->id));

        $response->assertStatus(403);
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

    public function test_member_can_access_mobile_profile_page(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
        ]);

        MemberProfile::create([
            'user_id' => $member->id,
            'member_code' => 'MEM-123456',
        ]);

        $response = $this->actingAs($member)->get(route('mobile.member.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Budi Pratama');
        $response->assertSee('budi@example.com');
        $response->assertSee('MEM-123456');
        $response->assertSee('Edit Informasi Akun');
        $response->assertSee('Perbarui Kata Sandi');
        $response->assertSee('Keluar dari Akun');
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
