<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariableValue extends Model
{
    protected $fillable = [
        'variable_id',
        'page_id',
        'value',
    ];

    public $timestamps = false;
}
