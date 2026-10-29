<?php

namespace Tests\Feature\Admin;

use App\Enums\PtSessionStatus;
use App\Mail\TrainerCredentialsMail;
use App\Models\MemberPtQuota;
use App\Models\PtSession;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrainerManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_trainer_management_page(): void
    {
        $trainer = User::factory()->trainer()->create(['name' => 'Coach Budi Santoso']);
        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Muscle Building',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.trainers.index'));

        $response->assertOk();
        $response->assertSee('Manajemen Data Personal Trainer');
        $response->assertSee('Coach Budi Santoso');
        $response->assertSee('Muscle Building');
    }

    public function test_member_cannot_access_trainer_management(): void
    {
        $member = User::factory()->member()->create();

        $response = $this->actingAs($member)->get(route('admin.trainers.index'));

        $response->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.trainers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_create_new_trainer_and_email_credentials_is_sent(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Coach Doni Pratama',
            'email' => 'donipratama@fitcore.my.id',
            'phone' => '081298765432',
            'specialization' => 'Strength & Conditioning',
            'experience' => 5,
            'bio' => 'Pelatih bersertifikasi dengan fokus strength training.',
            'password' => 'RahasiaTrainer123',
            'send_email' => '1',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.trainers.store'), $payload);

        $response->assertRedirect(route('admin.trainers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Coach Doni Pratama',
            'email' => 'donipratama@fitcore.my.id',
            'role' => 'trainer',
            'status' => 'active',
        ]);

        $createdTrainer = User::where('email', 'donipratama@fitcore.my.id')->first();
        $this->assertNotNull($createdTrainer);

        $this->assertDatabaseHas('trainer_profiles', [
            'user_id' => $createdTrainer->id,
            'specialization' => 'Strength & Conditioning',
            'experience' => 5,
            'status' => 'active',
        ]);

        Mail::assertSent(TrainerCredentialsMail::class, function ($mail) use ($createdTrainer) {
            return $mail->hasTo('donipratama@fitcore.my.id') &&
                $mail->trainer->id === $createdTrainer->id &&
                $mail->plainPassword === 'RahasiaTrainer123';
        });
    }

    public function test_admin_cannot_create_trainer_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@fitcore.my.id']);

        $payload = [
            'name' => 'Trainer Duplikat',
            'email' => 'existing@fitcore.my.id',
            'phone' => '081234567890',
            'specialization' => 'Cardio',
            'experience' => 2,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.trainers.store'), $payload);

        $response->assertSessionHasErrors('email');
        $this->assertEquals(1, User::where('email', 'existing@fitcore.my.id')->count());
    }

    public function test_admin_can_update_existing_trainer(): void
    {
        $trainer = User::factory()->trainer()->create([
            'name' => 'Coach Lama',
            'email' => 'coach.lama@fitcore.my.id',
            'phone' => '0811111111',
            'status' => 'active',
        ]);

        $profile = TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Old Spec',
            'experience' => 1,
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Coach Baru Diperbarui',
            'email' => 'coach.baru@fitcore.my.id',
            'phone' => '0822222222',
            'specialization' => 'Weight Loss Specialist',
            'experience' => 4,
            'bio' => 'Bio baru yang diperbarui.',
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.trainers.update', $trainer), $payload);

        $response->assertRedirect(route('admin.trainers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $trainer->id,
            'name' => 'Coach Baru Diperbarui',
            'email' => 'coach.baru@fitcore.my.id',
        ]);

        $this->assertDatabaseHas('trainer_profiles', [
            'user_id' => $trainer->id,
            'specialization' => 'Weight Loss Specialist',
            'experience' => 4,
        ]);
    }

    public function test_admin_can_update_trainer_phone_with_local_test_email(): void
    {
        $trainer = User::factory()->trainer()->create([
            'name' => 'Trainer Utama',
            'email' => 'trainer@fitcore.test',
            'phone' => '081299990005',
            'status' => 'active',
        ]);

        TrainerProfile::factory()->create([
            'user_id' => $trainer->id,
            'specialization' => 'Fat Loss & Functional Fitness',
            'experience' => 5,
            'status' => 'active',
        ]);

        $payload = [
            'name' => 'Trainer Utama',
            'email' => 'trainer@fitcore.test',
            'phone' => '081234567890',
            'specialization' => 'Fat Loss & Functional Fitness',
            'experience' => 5,
            'status' => 'active',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.trainers.update', $trainer), $payload);

        $response->assertRedirect(route('admin.trainers.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('081234567890', $trainer->fresh()->phone);
    }

    public function test_admin_can_toggle_trainer_status(): void
    {
        $trainer = User::factory()->trainer()->create(['status' => 'active']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id, 'status' => 'active']);

        // Nonaktifkan
        $response = $this->actingAs($this->admin)->patch(route('admin.trainers.toggle-status', $trainer));
        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertEquals('inactive', $trainer->fresh()->status);
        $this->assertEquals('inactive', $trainer->trainerProfile->fresh()->status);

        // Aktifkan kembali
        $response = $this->actingAs($this->admin)->patch(route('admin.trainers.toggle-status', $trainer));
        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertEquals('active', $trainer->fresh()->status);
        $this->assertEquals('active', $trainer->trainerProfile->fresh()->status);
    }

    public function test_admin_can_delete_trainer_without_active_sessions(): void
    {
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $response = $this->actingAs($this->admin)->delete(route('admin.trainers.destroy', $trainer));

        $response->assertRedirect(route('admin.trainers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $trainer->id]);
        $this->assertDatabaseMissing('trainer_profiles', ['user_id' => $trainer->id]);
    }

    public function test_admin_cannot_delete_trainer_with_active_pt_sessions(): void
    {
        $trainer = User::factory()->trainer()->create();
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $member = User::factory()->member()->create();
        $quota = MemberPtQuota::factory()->create(['member_id' => $member->id]);

        PtSession::factory()->create([
            'member_id' => $member->id,
            'trainer_id' => $trainer->id,
            'member_pt_quota_id' => $quota->id,
            'status' => PtSessionStatus::Confirmed->value,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.trainers.destroy', $trainer));

        $response->assertRedirect(route('admin.trainers.index'));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $trainer->id]);
    }
}
