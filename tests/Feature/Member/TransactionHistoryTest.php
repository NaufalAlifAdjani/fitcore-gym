<?php

namespace Tests\Feature\Member;

use App\Models\Bank;
use App\Models\MembershipPackage;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TransactionHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_riwayat_transaksi(): void
    {
        $response = $this->get(route('member.riwayat-transaksi.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_view_transaction_history_grouped_by_month_and_year(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'FitCore Starter Pass',
            'badge' => 'SILVER',
            'tier' => 'Basic',
            'price' => 450000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        $octDate = Carbon::create(2026, 10, 15, 10, 0, 0);
        $sepDate = Carbon::create(2026, 9, 10, 14, 0, 0);

        Payment::create([
            'invoice_id' => 'INV-OCT-2026-001',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 450000,
            'bank_sender' => 'BCA',
            'bank_destination' => 'FitCore BCA',
            'transfer_date' => $octDate,
            'status' => 'verified',
        ]);

        Payment::create([
            'invoice_id' => 'INV-SEP-2026-002',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 450000,
            'bank_sender' => 'Mandiri',
            'bank_destination' => 'FitCore BCA',
            'transfer_date' => $sepDate,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($member)->get(route('member.riwayat-transaksi.index'));

        $response->assertOk()
            ->assertSee('RIWAYAT TRANSAKSI')
            ->assertSee('OKTOBER 2026')
            ->assertSee('SEPTEMBER 2026')
            ->assertSee('INV-OCT-2026-001')
            ->assertSee('INV-SEP-2026-002')
            ->assertSee('FitCore Starter Pass')
            ->assertDontSee('Estimasi verifikasi finance: ± 15-30 menit');
    }

    public function test_member_can_filter_transactions_by_status(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'FitCore Gold Pass',
            'tier' => 'Premium',
            'price' => 750000,
            'duration_in_days' => 60,
            'is_active' => true,
        ]);

        Payment::create([
            'invoice_id' => 'INV-PENDING-01',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 750000,
            'status' => 'pending',
            'transfer_date' => now(),
        ]);

        Payment::create([
            'invoice_id' => 'INV-VERIFIED-02',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 750000,
            'status' => 'verified',
            'transfer_date' => now(),
        ]);

        // Filter status pending
        $pendingResponse = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', ['status' => 'pending']));
        $pendingResponse->assertOk()
            ->assertSee('INV-PENDING-01')
            ->assertDontSee('INV-VERIFIED-02');

        // Filter status verified
        $verifiedResponse = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', ['status' => 'verified']));
        $verifiedResponse->assertOk()
            ->assertSee('INV-VERIFIED-02')
            ->assertDontSee('INV-PENDING-01');
    }

    public function test_member_can_filter_transactions_by_search_query(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BRI',
            'account_number' => '9988776655',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $packageA = MembershipPackage::create([
            'name' => 'Calisthenics Beast',
            'tier' => 'Special',
            'price' => 500000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        $packageB = MembershipPackage::create([
            'name' => 'Pilates Reformer Access',
            'tier' => 'Special',
            'price' => 850000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        Payment::create([
            'invoice_id' => 'INV-CALISTHENICS-123',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $packageA->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 500000,
            'status' => 'verified',
            'transfer_date' => now(),
        ]);

        Payment::create([
            'invoice_id' => 'INV-PILATES-456',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $packageB->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 850000,
            'status' => 'verified',
            'transfer_date' => now(),
        ]);

        // Search by invoice
        $invoiceSearch = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', ['search' => 'CALISTHENICS-123']));
        $invoiceSearch->assertOk()
            ->assertSee('INV-CALISTHENICS-123')
            ->assertDontSee('INV-PILATES-456');

        // Search by package name
        $nameSearch = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', ['search' => 'Pilates']));
        $nameSearch->assertOk()
            ->assertSee('INV-PILATES-456')
            ->assertDontSee('INV-CALISTHENICS-123');
    }

    public function test_member_can_filter_transactions_by_year(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'FitCore Yearly Member',
            'tier' => 'VIP',
            'price' => 3500000,
            'duration_in_days' => 365,
            'is_active' => true,
        ]);

        Payment::create([
            'invoice_id' => 'INV-YEAR-2025-01',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 3500000,
            'status' => 'verified',
            'transfer_date' => Carbon::create(2025, 6, 1, 10, 0, 0),
        ]);

        Payment::create([
            'invoice_id' => 'INV-YEAR-2026-02',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 3500000,
            'status' => 'verified',
            'transfer_date' => Carbon::create(2026, 6, 1, 10, 0, 0),
        ]);

        $response = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', ['year' => '2025']));

        $response->assertOk()
            ->assertSee('INV-YEAR-2025-01')
            ->assertDontSee('INV-YEAR-2026-02');
    }

    public function test_empty_state_shown_when_no_transactions_found(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get(route('member.riwayat-transaksi.index', [
            'search' => 'KATA_KUNCI_TIDAK_ADA',
        ]));

        $response->assertOk()
            ->assertSee('Riwayat tidak ditemukan');
    }

    public function test_member_can_download_invoice_pdf_for_own_transaction(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'FitCore Pro Athlete',
            'tier' => 'Pro',
            'price' => 1200000,
            'duration_in_days' => 90,
            'is_active' => true,
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-DOWNLOAD-PDF-01',
            'member_id' => $member->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 1200000,
            'status' => 'verified',
            'transfer_date' => now(),
        ]);

        $response = $this->actingAs($member)->get(route('member.riwayat-transaksi.invoice', $payment));

        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment; filename="Invoice-INV-DOWNLOAD-PDF-01.pdf"', (string) $response->headers->get('Content-Disposition'));

        $content = $response->streamedContent ?? $response->getContent();
        $this->assertStringStartsWith('%PDF-1.4', $content);
        $this->assertStringEndsWith('%%EOF', trim($content));
    }

    public function test_member_cannot_download_other_members_invoice_pdf(): void
    {
        $owner = User::factory()->create(['role' => 'member']);
        $stranger = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Pass',
            'tier' => 'Basic',
            'price' => 100000,
            'duration_in_days' => 7,
            'is_active' => true,
        ]);

        $payment = Payment::create([
            'invoice_id' => 'INV-OWNER-PRIVATE-01',
            'member_id' => $owner->id,
            'bank_id' => $bank->id,
            'package_id' => $package->id,
            'package_type' => 'membership',
            'payment_type' => 'membership',
            'amount' => 100000,
            'status' => 'verified',
            'transfer_date' => now(),
        ]);

        $response = $this->actingAs($stranger)->get(route('member.riwayat-transaksi.invoice', $payment));

        $response->assertForbidden();
    }

    public function test_no_n_plus_one_queries_when_listing_transactions(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $bank = Bank::create([
            'name' => 'BCA',
            'account_number' => '1234567890',
            'account_holder' => 'FitCore Gym',
            'status' => 'active',
        ]);

        $package = MembershipPackage::create([
            'name' => 'Pass Test',
            'tier' => 'Basic',
            'price' => 100000,
            'duration_in_days' => 30,
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 10; $i++) {
            Payment::create([
                'invoice_id' => "INV-BULK-{$i}",
                'member_id' => $member->id,
                'bank_id' => $bank->id,
                'package_id' => $package->id,
                'package_type' => 'membership',
                'payment_type' => 'membership',
                'amount' => 100000,
                'status' => 'verified',
                'transfer_date' => now()->subDays($i),
            ]);
        }

        DB::flushQueryLog();
        DB::enableQueryLog();

        $response = $this->actingAs($member)->get(route('member.riwayat-transaksi.index'));

        $response->assertOk();

        $queryCount = count(DB::getQueryLog());
        // Eager loaded queries should be well below 10 (usually ~3-4 queries total for session/auth, years, and payments with relations)
        $this->assertLessThanOrEqual(6, $queryCount);
    }
}
