<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable = [
        'snapshot_id',
        'selector',
        'scanned_pages',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function isCompleted()
    {
        return $this->scanned_pages >= $this->snapshot->total;
    }
}
