<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Jobs\DownloadPage;

class Snapshot extends Model
{
    protected $fillable = [
        'name',
        'key',
        'url',
        'from',
        'to',
        'refresh_daily',
    ];

    protected $hidden = [
        'user_id',
        'deleted_at',
    ];

    public static function validator()
    {
        return [
            'name' => 'required',
            'url' => 'required|url|regex:/\*/',
            'from' => 'required|integer|lte:to|min:1',
            'to' => 'required|integer|gte:from|min:1',
            'refresh_daily' => 'boolean',
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

        $this->save(['timestamps' => false]);

        return $nextPageUrl;
    }

    public function retry()
    {
        if ($lastPage = $this->pages()->latest()->first()) {
            $this->pages()->latest()->first()->delete();

            $this->current--;

            $this->save();
        }

        return DownloadPage::dispatch($this);
    }

    // download all pages
    public function download()
    {
        $this->pages()->delete();

        $this->current = 0;

        $this->status = 'in_progress';

        $this->save();

        return DownloadPage::dispatch($this);
    }

    public function isCompleted()
    {
        if ($this->current === $this->to) {
            $this->status = 'completed';

            $this->save();
        }

        return $this->status === 'completed';
    }

    public function stop()
    {
        $this->status = 'stopped';

        $this->save();
    }

    public function isStopped()
    {
        return $this->status === 'stopped';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function delete()
    {
        $this->pages()->delete();

        foreach ($this->variables as $variable) {
            $variable->values()->delete();
        }

        parent::delete();
    }

    public function getRefreshedAtAttribute()
    {
        $lastPage = $this->pages()->latest()->first();
        return $lastPage ? $lastPage->updated_at : null;
    }
}
