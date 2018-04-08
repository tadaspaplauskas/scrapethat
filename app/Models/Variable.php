<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = [
        'snapshot_id',
        'name',
        'selector',
        'scanned',
        'values',
        'selected',
    ];

    protected $casts = [
        'values' => 'collection',
        'selected' => 'boolean',
    ];

    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function isCompleted()
    {
        return $this->scanned >= $this->snapshot->pages()->count();
    }
}
