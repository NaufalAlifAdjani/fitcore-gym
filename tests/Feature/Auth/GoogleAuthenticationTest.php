<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_to_google_initiates_oauth(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location') ?? '');
    }

    public function test_google_callback_creates_and_authenticates_new_user(): void
    {
        $mockSocialiteUser = Mockery::mock(SocialiteUser::class);
        $mockSocialiteUser->shouldReceive('getId')->andReturn('google-id-12345');
        $mockSocialiteUser->shouldReceive('getEmail')->andReturn('newuser@example.com');
        $mockSocialiteUser->shouldReceive('getName')->andReturn('Google User');
        $mockSocialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/avatar');

        $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $mockProvider->shouldReceive('user')->once()->andReturn($mockSocialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'google_id' => 'google-id-12345',
            'role' => 'member',
        ]);
    }

    public function test_google_callback_falls_back_to_stateless_on_invalid_state_exception(): void
    {
        $mockSocialiteUser = Mockery::mock(SocialiteUser::class);
        $mockSocialiteUser->shouldReceive('getId')->andReturn('google-id-stateless');
        $mockSocialiteUser->shouldReceive('getEmail')->andReturn('stateless@example.com');
        $mockSocialiteUser->shouldReceive('getName')->andReturn('Stateless User');
        $mockSocialiteUser->shouldReceive('getAvatar')->andReturn(null);

        $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $mockProvider->shouldReceive('user')->once()->andThrow(new InvalidStateException);
        $mockProvider->shouldReceive('stateless')->once()->andReturnSelf();
        $mockProvider->shouldReceive('user')->once()->andReturn($mockSocialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $this->assertAuthenticated();
        $response->assertRedirect(route('member.dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'stateless@example.com',
            'google_id' => 'google-id-stateless',
        ]);
    }

    public function test_google_callback_redirects_with_error_when_email_is_missing(): void
    {
        $mockSocialiteUser = Mockery::mock(SocialiteUser::class);
        $mockSocialiteUser->shouldReceive('getId')->andReturn('google-id-no-email');
        $mockSocialiteUser->shouldReceive('getEmail')->andReturn(null);
        $mockSocialiteUser->shouldReceive('getName')->andReturn('No Email');
        $mockSocialiteUser->shouldReceive('getAvatar')->andReturn(null);

        $mockProvider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $mockProvider->shouldReceive('user')->once()->andReturn($mockSocialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($mockProvider);

        $response = $this->get(route('auth.google.callback'));

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['google']);
    }
}
