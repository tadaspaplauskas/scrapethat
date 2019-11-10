<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Query extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'query',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
