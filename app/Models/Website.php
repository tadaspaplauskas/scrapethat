<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
    }
    
}
