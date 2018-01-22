<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\ProcessFilter;
use App\Filter;

class ProcessFilterTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testProcessFilter()
    {
        $filter = Filter::first();

        $this->assertTrue($filter->scanned == 0);

        $job = new ProcessFilter($filter);

        $job->handle();

        $filter->refresh();

        $this->assertTrue($filter->scanned > 0);
        $this->assertTrue($filter->isCompleted());
        $this->assertFalse($filter->values->isEmpty());
        $this->assertTrue($filter->values->contains('needle'));
    }
}
