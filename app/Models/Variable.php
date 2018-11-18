<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\CssSelector\Exception\SyntaxErrorException;
use App\Jobs\ProcessVariable;
use Symfony\Component\DomCrawler\Crawler;

class Variable extends Model
{
    protected $fillable = [
        'snapshot_id',
        'name',
        'selector',
        'current_page',
        'type',
    ];

    const TYPES = ['numeric', 'text'];

    public static function validator()
    {
        return [
            'name' => [
                'required',
                'alpha_num',
            ],
            'type' => [
                'in:' . implode(',', static::TYPES),
            ],
            'selector' => [
                'required',
                // verify that the selector is valid
                function($attribute, $value, $fail) {
                    $converter = new CssSelectorConverter();

                    try {
                        $converter->toXPath($value);
                    }
                    catch (SyntaxErrorException $e) {
                        return $fail('The ' . $attribute . ' must be a valid CSS selector.');
                    }
                },

                // verify that there's data for that selector
                // using closure to pass data to it; class would not work like that
                function($attribute, $value, $fail) use ($snapshot) {
                    $page = $snapshot->pages()->first();

                    if (!$page) {
                        return $fail('There are no pages in the snapshot');
                    }

                    $crawler = new Crawler($page->html);

                    if (!$crawler->filter($value)->count()) {
                        return $fail('Provided ' . $attribute . ' does not match anything on the page');
                    }
                }
            ],
        ];
    }
    public function snapshot()
    {
        return $this->belongsTo(Snapshot::class);
    }

    public function values()
    {
        return $this->hasMany(VariableValue::class, 'variable_id');
    }

    public function isCompleted()
    {
        return $this->current_page >= $this->snapshot->pages()->count();
    }

    public function isNumeric()
    {
        return $this->type === 'numeric';
    }

    public function process()
    {
        $this->current_page = 0;
        $this->save();

        $this->values()->delete();

        ProcessVariable::dispatch($this);
    }
}
