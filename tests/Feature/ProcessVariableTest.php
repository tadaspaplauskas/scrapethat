<?php

namespace Tests\Feature;

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

    public function testProcessVariable()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $page = $snapshot->pages()->save(factory(Page::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $this->assertTrue($variable->current_page == 0);

        $job = new ProcessVariable($variable);

        $job->handle();

        $variable->refresh();

        $this->assertTrue($variable->current_page > 0);

        $this->assertTrue($variable->isCompleted());

        $this->seeInDatabase('variable_values', ['value' => '73 800 €']);
    }
}
