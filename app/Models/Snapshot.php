<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Snapshot extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'url',
        'from',
        'to',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
