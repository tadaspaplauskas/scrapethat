<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable = [
        'snapshot_id',
        'selector',
        'scanned',
        'values',
    ];

    protected $casts = [
        'values' => 'collection',
    ];

    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function isCompleted()
    {
        return $this->scanned >= $this->snapshot->total;
    }
}
