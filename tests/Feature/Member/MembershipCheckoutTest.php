<?php

namespace Tests\Feature\Member;

use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MembershipCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_member_can_view_membership_package_catalog(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $package = MembershipPackage::create([
            'name' => 'FitCore Starter Pass',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'description' => 'Akses pemula hemat',
            'price' => 450000,
            'promo_price' => null,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Akses Gym'],
            'pt_sessions' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($member)->get(route('member.membership.packages'));

        $response->assertOk()
            ->assertSee('Pilih Paket Keanggotaan')
            ->assertSee('FitCore Starter Pass')
            ->assertSee('450.000');
    }

    public function test_member_can_access_checkout_form_for_active_package(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'PT FitCore Gym',
            'status' => 'active',
        ]);
        $package = MembershipPackage::create([
            'name' => 'FitCore Elite Champion',
            'badge' => 'BEST SELLER',
            'tier' => 'Premium & VIP',
            'description' => 'Akses lengkap',
            'price' => 2400000,
            'promo_price' => 1800000,
            'duration_value' => 6,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 180,
            'facilities' => ['Akses Gym 24 jam'],
            'pt_sessions' => 2,
            'is_active' => true,
        ]);

        $response = $this->actingAs($member)->get(route('member.membership.checkout', $package));

        $response->assertOk()
            ->assertSee('FitCore Elite Champion')
            ->assertSee('BCA')
            ->assertSee('1234567890')
            ->assertSee('Form Bukti Transfer');
    }

    public function test_validation_fails_if_proof_image_is_missing_or_invalid(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'Mandiri',
            'account_number' => '9876543210',
            'account_holder' => 'PT FitCore Gym',
            'status' => 'active',
        ]);
        $package = MembershipPackage::create([
            'name' => 'Basic Package',
            'tier' => 'Basic',
            'price' => 300000,
            'duration_value' => 1,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 30,
            'facilities' => ['Gym access'],
            'is_active' => true,
        ]);

        // Missing proof_image and sender_name
        $response = $this->actingAs($member)
            ->post(route('member.membership.process-payment', $package), [
                'bank_id' => $bank->id,
            ]);

        $response->assertSessionHasErrors(['sender_name', 'proof_image']);

        // Invalid file format (text file)
        Storage::fake('public');
        $textDocument = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $invalidResponse = $this->actingAs($member)
            ->post(route('member.membership.process-payment', $package), [
                'bank_id' => $bank->id,
                'sender_name' => 'Ahmad Pengirim',
                'proof_image' => $textDocument,
            ]);

        $invalidResponse->assertSessionHasErrors(['proof_image']);
    }

    public function test_member_successfully_uploads_proof_of_payment_and_creates_pending_record(): void
    {
        Storage::fake('public');

        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BRI',
            'account_number' => '1122334455',
            'account_holder' => 'PT FitCore Gym',
            'status' => 'active',
        ]);
        $package = MembershipPackage::create([
            'name' => 'FitCore Annual Pro',
            'badge' => 'PLATINUM VIP',
            'tier' => 'Premium & VIP',
            'description' => 'Akses 1 tahun penuh',
            'price' => 5000000,
            'promo_price' => 4500000,
            'duration_value' => 12,
            'duration_unit' => 'Bulan',
            'duration_in_days' => 365,
            'facilities' => ['Akses semua cabang'],
            'pt_sessions' => 5,
            'is_active' => true,
        ]);

        $fakeReceipt = UploadedFile::fake()->image('receipt.jpg', 600, 800);

        $response = $this->actingAs($member)
            ->post(route('member.membership.process-payment', $package), [
                'bank_id' => $bank->id,
                'sender_name' => 'Budi Santoso',
                'proof_image' => $fakeReceipt,
            ]);

        $payment = Payment::query()->first();

        $this->assertNotNull($payment);
        $response->assertRedirect(route('member.membership.payment-status', $payment));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'member_id' => $member->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 4500000,
            'bank_id' => $bank->id,
            'bank_sender' => 'Budi Santoso',
            'status' => 'pending',
        ]);

        $this->assertTrue(Storage::disk('public')->exists($payment->proof_image_path));
    }
}
