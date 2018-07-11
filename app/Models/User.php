<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use Notifiable, Billable;

    // possible subscription plans
    // braintree_plan => 'title'
    const PLANS = [
        'uno' => '10 000 pages / $10 monthly',
        'dos' => '100 000 pages / $20 monthly',
        'tres' => '100 000 pages / $200 yearly',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token',
    ];

    public function snapshots()
    {
        return $this->hasMany(Snapshot::class);
    }

    public function withinLimits()
    {

    }
}
