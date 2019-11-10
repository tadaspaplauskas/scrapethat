<?php

namespace Tests\Feature;

use Tests\BrowserTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Query;

class QueryTest extends BrowserTestCase
{
    use RefreshDatabase;

    public function testQueryIndex()
    {
        $user = factory(User::class)->create();

        $queries = $user->queries()->saveMany(factory(Query::class, 10)->make());

        $this->actingAs($user)
            ->visit('/queries')
            ->see('Queries');

        $queries->each(function ($q) {
            $this->see($q->name);
        });
    }

    public function testQueryCreate()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->visitRoute('queries.create')
            ->type('Sample query', 'name')
            ->type('SELECT true', 'query')
            ->press('Save')
            ->seeRouteIs('queries.index')
            ->see('Sample query');
    }

    public function testQueryDelete()
    {
        $user = factory(User::class)->create();

        $query = $user->queries()->save(factory(Query::class)->make());

        $this->assertTrue(Query::all()->isNotEmpty());

        $this->actingAs($user)
            ->visitRoute('queries.index')
            ->see($query->name)
            ->click('Delete')
            ->press('Delete')
            ->see('was deleted');

        $this->assertTrue(Query::all()->isEmpty());
    }
}
