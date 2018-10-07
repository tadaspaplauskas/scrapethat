<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function api()
    {
        $token = Auth::check() ? Auth::user()->api_token : '';

        $url = url('/api/v1/');

        $now = \Carbon\Carbon::now();

        return view('pages.api', compact('token', 'url', 'now'));
    }
}
