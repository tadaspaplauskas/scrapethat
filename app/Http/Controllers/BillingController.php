<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function plan()
    {

        return view('billing.plan');
    }

    public function setPlan()
    {
        $user->newSubscription('1k', '1k')->create($stripeToken);
    }
}
