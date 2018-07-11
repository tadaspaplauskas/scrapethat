<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function subscription()
    {
        $plans = static::PLANS;

        $braintreePlan = Auth::user()->subscription('main')->braintree_plan ?? 'uno';
        $current = static::PLANS[$braintreePlan];

        return view('subscription', compact('plans', 'current'));
    }

    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'nonce' => 'required',
            'plan' => 'in:uno,dos,tres',
        ]);

        $user = Auth::user();

        // update existing plan
        if ($user->subscribed('main')) {
            $user->subscription('main')->swap($data['plan']);
        }
        else {
            $user->newSubscription('main', $data['plan'])->create($data['nonce']);
        }

        return redirect()->back()
            ->with('message', 'Thank you!');
    }
}
