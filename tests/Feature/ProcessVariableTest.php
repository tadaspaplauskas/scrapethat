<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\ProcessVariable;
use App\User;
use App\Snapshot;
use App\Variable;
use App\Page;

class ProcessVariableTest extends TestCase
{
    use RefreshDatabase;

    public function testProcessVariable()
    {
        $user = factory(User::class)->create();
        $snapshot = $user->snapshots()->save(factory(Snapshot::class)->make());
        $page = $snapshot->pages()->save(factory(Page::class)->make());
        $variable = $snapshot->variables()->save(factory(Variable::class)->make());

        $variable->values = null;

        $this->assertTrue($variable->scanned == 0);

        $job = new ProcessVariable($variable);

        $job->handle();

        $variable->refresh();

        $this->assertTrue($variable->scanned > 0);
        $this->assertTrue($variable->isCompleted());
        $this->assertFalse($variable->values->isEmpty());
        $this->assertTrue($variable->values->contains('73 800 €'));
    }
}
