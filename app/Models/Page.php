<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $fillable = [
        'snapshot_id',
        'url',
        // 'from',
        // 'to',
        'dom',
    ];
}
