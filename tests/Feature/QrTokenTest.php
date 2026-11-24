<?php

namespace Tests\Feature;

use App\Models\MemberProfile;
use App\Models\QrToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for Dynamic QR Token generation endpoint.
 */
class QrTokenTest extends TestCase
{
    use RefreshDatabase;

    private function memberUser(): User
    {
        $member = User::factory()->create(['role' => 'member']);
        MemberProfile::factory()->create(['user_id' => $member->id]);

        return $member;
    }

    /**
     * TC-QR-1: Authenticated member can generate a QR token.
     */
    public function test_member_can_generate_qr_token(): void
    {
        $member = $this->memberUser();

        $response = $this->actingAs($member)
            ->postJson(route('member.card.qr-token'));

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'expires_at_unix', 'ttl']);

        $token = $response->json('token');
        $this->assertDatabaseHas('qr_tokens', [
            'member_id' => $member->id,
            'token' => $token,
            'used_at' => null,
        ]);
    }

    /**
     * TC-QR-2: Generating a new token invalidates the previous pending token.
     */
    public function test_generating_new_token_invalidates_previous_tokens(): void
    {
        $member = $this->memberUser();

        // Create an existing unexpired token
        $oldToken = bin2hex(random_bytes(32));
        QrToken::create([
            'member_id' => $member->id,
            'token' => $oldToken,
            'expires_at' => now()->addSeconds(30),
            'used_at' => null,
        ]);

        // Generate a new token — old one should be deleted
        $this->actingAs($member)->postJson(route('member.card.qr-token'));

        $this->assertDatabaseMissing('qr_tokens', ['token' => $oldToken]);
    }

    /**
     * TC-QR-3: Unauthenticated request → redirected / 401.
     */
    public function test_unauthenticated_cannot_generate_token(): void
    {
        $response = $this->postJson(route('member.card.qr-token'));
        $response->assertStatus(401);
    }

    /**
     * TC-QR-4: Admin cannot access the member token generation endpoint.
     */
    public function test_admin_cannot_generate_member_qr_token(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->postJson(route('member.card.qr-token'));

        $response->assertStatus(403);
    }

    /**
     * TC-QR-5: Token returned is 64 hex characters (32 random bytes).
     */
    public function test_generated_token_is_64_hex_characters(): void
    {
        $member = $this->memberUser();

        $response = $this->actingAs($member)
            ->postJson(route('member.card.qr-token'));

        $token = $response->json('token');
        $this->assertMatchesRegularExpression('/^[0-9a-f]{64}$/', $token);
    }

    /**
     * TC-QR-6: TTL field equals 30 seconds.
     */
    public function test_token_ttl_is_30_seconds(): void
    {
        $member = $this->memberUser();

        $response = $this->actingAs($member)
            ->postJson(route('member.card.qr-token'));

        $response->assertJsonFragment(['ttl' => 30]);
    }
}
