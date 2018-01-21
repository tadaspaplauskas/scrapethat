<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $fillable = [
        'snapshot_id',
        'url',
        'status_code',
        'reason_phrase',
        'html',
    ];
}
