<?php

namespace Tests\Feature\API;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Snapshot;
use App\Models\Page;
use App\Models\Variable;

class VariableTest extends TestCase
{
    use RefreshDatabase;

    public function testVariableIndex()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/snapshots/' . $snapshot->id . '/variables')
            ->assertStatus(200)
            ->assertJsonFragment(['name' => 'price']);
    }

    public function testVariableStore()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $snapshot->pages()->saveMany(factory(Page::class, 5)->make());

        $this->actingAs($user, 'api')
            ->json('POST', '/api/v1/snapshots/' . $snapshot->id . '/variables', [
                'name' => 'score',
                'selector' => '.score',
                'type' => 'numeric',
            ])
            ->assertStatus(201)
            ->assertJson(['name' => 'score']);
    }

    public function testVariableUpdate()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $snapshot->pages()->saveMany(factory(Page::class, 5)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user, 'api')
            ->json('PUT', '/api/v1/variables/' . $variable->id, [
                'name' => 'updated',
                'selector' => '.title',
                'type' => 'numeric',
            ])
            ->assertStatus(200)
            ->assertJson([
                'name' => 'updated',
                'selector' => '.title',
                'type' => 'numeric',
            ]);
    }

    public function testVariableShow()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user, 'api')
            ->json('GET', '/api/v1/variables/' . $variable->id)
            ->assertStatus(200)
            ->assertJson(['name' => $variable->name]);
    }

    public function testVariableDestroy()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/v1/variables/' . $variable->id)
            ->assertStatus(204);
    }
}
