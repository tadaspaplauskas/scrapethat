<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function subscription()
    {
        $user = Auth::user();

        $plans = User::PLANS;

        $subscription = Auth::user()->subscription('main');

        if ($user->onTrial()) {
            $current = '7-Day trial';
        }
        else if ($subscription->cancelled()) {
            $current = 'cancelled';
        }
        else {
            $current = User::PLANS[$subscription->braintree_plan];
        }

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

    public function cancel(Request $request)
    {
        $user = Auth::user();

        $subscription = $user->subscription('main');

        if ($subscription) {
            $subscription->cancel();
        }

        return redirect()->back()
            ->with('message', 'Subscription was cancelled.');
    }
}
