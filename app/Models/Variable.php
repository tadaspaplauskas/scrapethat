<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    protected $fillable = [
        'snapshot_id',
        'name',
        'selector',
        'current_page',
        'selected',
    ];

    protected $casts = [
        'selected' => 'boolean',
    ];

    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function values()
    {
        return $this->hasMany(VariableValue::class, 'variable_id');
    }

    public function isCompleted()
    {
        return $this->current_page >= $this->snapshot->pages()->count();
    }
}
