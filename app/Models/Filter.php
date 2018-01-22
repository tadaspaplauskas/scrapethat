<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $fillable = [
        'snapshot_id',
        'selector',
        'data',
    ];
}
