<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\PopulateFilter;
use App\Filter;

class ProcessFilterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->seed(\DatabaseSeeder::class);
    }

    public function testPopulateFilter()
    {
        $filter = Filter::first();

        $this->assertTrue($filter->scanned == 0);

        $job = new PopulateFilter($filter);

        $job->handle();

        $filter->refresh();

        $this->assertTrue($filter->scanned > 0);
        $this->assertTrue($filter->isCompleted());
        $this->assertFalse($filter->values->isEmpty());
        $this->assertTrue($filter->values->contains('needle'));
    }
}
