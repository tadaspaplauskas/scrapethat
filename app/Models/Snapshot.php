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
        'crawled',
        'total',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function filters()
    {
        return $this->hasMany(Filter::class);
    }

    public function nextPageUrl()
    {
        // we're done here
        if ($this->isCompleted()) {
            return null;
        }

        $nextPage = $this->from + $this->crawled;

        $nextPageUrl = str_replace('*', $nextPage, $this->url);
        
        return $nextPageUrl;
    }

    public function isCompleted()
    {
        return $this->crawled > 0 && $this->crawled === $this->total;
    }
}
