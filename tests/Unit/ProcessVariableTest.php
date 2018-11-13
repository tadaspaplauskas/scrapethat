<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessVariable;
use App\Models\User;
use App\Models\Snapshot;
use App\Models\Variable;
use App\Models\VariableValue;
use App\Models\Page;

class ProcessVariableTest extends TestCase
{
    use RefreshDatabase;

    public function testProcessNumericVariable()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make([
            'type' => 'numeric',
        ]));

        $snapshot->download();

        $this->assertTrue($variable->current_page == 0);

        $job = new ProcessVariable($variable);

        $job->handle();

        $variable->refresh();

        $this->assertTrue($variable->current_page > 0);

        $this->assertTrue($variable->isCompleted());

        $this->assertDatabaseHas('variable_values', ['value' => '73800']);
    }

    public function testProcessTextVariable()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make([
            'type' => 'text',
        ]));

        $snapshot->download();

        $this->assertTrue($variable->current_page == 0);

        $job = new ProcessVariable($variable);

        $job->handle();

        $variable->refresh();

        $this->assertTrue($variable->current_page > 0);

        $this->assertTrue($variable->isCompleted());

        $this->assertDatabaseHas('variable_values', ['value' => '73 800 €']);
    }
}
