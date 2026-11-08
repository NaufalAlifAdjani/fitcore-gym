<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Member\Sessions\Index;
use App\Models\TrainerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MemberSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_sessions_can_switch_modes_and_filters()
    {
        $member = User::factory()->create(['role' => 'member']);
        $trainer = User::factory()->create(['role' => 'trainer']);
        TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $this->actingAs($member);

        $test = Livewire::test(Index::class)
            ->assertStatus(200)
            ->call('setMode', 'tasks')
            ->assertSet('mode', 'tasks')
            ->call('setTaskStatusFilter', 'pending')
            ->assertSet('taskStatusFilter', 'pending')
            ->call('nextPeriod')
            ->call('previousPeriod')
            ->call('setMode', 'calendar')
            ->assertSet('mode', 'calendar');

        $rootTag = $test->html();
        // Ensure root element has wire:id directly
        $this->assertMatchesRegularExpression('/^\s*<div[^>]+wire:id/m', $rootTag);
    }
}
