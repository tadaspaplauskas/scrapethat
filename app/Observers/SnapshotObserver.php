<?php

namespace App\Observers;

use App\Models\Snapshot;
use Illuminate\Support\Str;

class SnapshotObserver
{
    public function creating(Snapshot $snapshot)
    {
        $this->fillKey($snapshot);
    }

    public function updating(Snapshot $snapshot)
    {
        $this->fillKey($snapshot);
    }

    public function saving(Snapshot $snapshot)
    {
        $this->fillKey($snapshot);
    }

    protected function fillKey(Snapshot $snapshot)
    {
        $snapshot->key = Str::snake($snapshot->name);

        return $snapshot;
    }
}
