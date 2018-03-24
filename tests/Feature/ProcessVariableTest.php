<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\ProcessVariable;
use App\Variable;

class ProcessVariableTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testProcessVariable()
    {
        $variable = Variable::first();

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
