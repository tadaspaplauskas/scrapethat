<?php

namespace Tests\Feature;

use Tests\BrowserTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class SubscriptionTest extends BrowserTestCase
{
    use RefreshDatabase;

    public function testSeeSubscription()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->visitRoute('subscription')
            ->see('Upgrade')
            ->see('Current plan')
            ->see('trial');
    }

    public function testCancelSubscription()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->visitRoute('subscription')
            ->press('Cancel')
            ->see('Subscription was cancelled');
    }
}
