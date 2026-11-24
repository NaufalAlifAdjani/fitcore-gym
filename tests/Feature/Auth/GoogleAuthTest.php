<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_redirect_redirects_to_google(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertStatus(302);
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_creates_and_logs_in_new_member(): void
    {
        $googleUser = Mockery::mock(SocialiteUser::class);
        $googleUser->shouldReceive('getId')->andReturn('google-id-12345');
        $googleUser->shouldReceive('getEmail')->andReturn('newgooglemember@example.com');
        $googleUser->shouldReceive('getName')->andReturn('Google Member');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        $driver = Mockery::mock();
        $driver->shouldReceive('stateless->user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard'));

        $user = User::where('email', 'newgooglemember@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('google-id-12345', $user->google_id);
        $this->assertEquals('member', $user->role);
        $this->assertNotNull($user->memberProfile);
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_google_callback_redirects_to_login_when_user_cancels(): void
    {
        $response = $this->get(route('auth.google.callback', ['error' => 'access_denied']));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('google');
    }

    public function test_google_callback_redirects_to_login_with_error_when_oauth_fails(): void
    {
        $driver = Mockery::mock();
        $driver->shouldReceive('stateless->user')->andThrow(new \Exception('Connection timeout'));

        Socialite::shouldReceive('driver')->with('google')->andReturn($driver);

        $response = $this->get(route('auth.google.callback'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('google');
    }
}
