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
        'downloaded',
        'total',
    ];

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

        $nextPage = $this->from + $this->downloaded;

        $nextPageUrl = str_replace('*', $nextPage, $this->url);
        
        return $nextPageUrl;
    }

    public function isCompleted()
    {
        return $this->downloaded > 0 && $this->downloaded === $this->total;
    }
}
