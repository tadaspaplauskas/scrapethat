<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function testSeeSubscription()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->visitRoute('subscription')
            ->see('Upgrade')
            ->see('Current plan')
            ->see(current(User::PLANS)); // default plan is the first one
    }

    // public function testCancelSubscription()
    // {
    //     $user = factory(User::class)->create();

    //     $this->actingAs($user)
    //         ->visitRoute('cancel')
    //         ->see('Sample snapshot');
    // }
}
