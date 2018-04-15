<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Snapshot extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'url',
        'from',
        'to',
        'current',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function variables()
    {
        return $this->hasMany(Variable::class);
    }

    public function nextPageUrl()
    {
        // we're done here
        if ($this->isCompleted()) {
            return null;
        }

        $this->current = $this->current < $this->from ? $this->from : $this->current + 1;
        
        $nextPageUrl = str_replace('*', $this->current, $this->url);

        $this->save();

        return $nextPageUrl;
    }

    public function isCompleted()
    {
        return $this->current === $this->to;
    }
}
