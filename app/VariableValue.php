<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VariableValue extends Model
{
    protected $fillable = [
        'value',
    ];

    public $timestamps = false;
}
