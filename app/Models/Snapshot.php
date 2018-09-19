<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\DownloadPage;

class Snapshot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'url',
        'from',
        'to',
        'current',
        'stopped',
    ];

    protected $hidden = [
        'user_id',
        'deleted_at',
    ];

    protected $casts = [
        'name',
        'url',
        'from' => 'integer',
        'to' => 'integer',
        'current' => 'integer',
        'stopped' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        // re-download snapshot after each update
        static::saved(function (Snapshot $snapshot) {
            DownloadPage::dispatch($snapshot);
        });
    }

    public static function validator()
    {
        return [
            'name' => 'required',
            'url' => 'required|url|regex:/\*/',
            'from' => 'required|integer|lte:to|min:1',
            'to' => 'required|integer|gte:from|min:1',
        ];
    }

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

    public function retry()
    {
        if ($lastPage = $this->pages()->latest()->first()) {
            $this->pages()->latest()->first()->delete();

            $this->current--;

            $this->save();
        }

        // event is dispatched automatically on `saved` event
    }

    public function stop()
    {

    }
}
