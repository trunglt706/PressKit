<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display guest about page.
     */
    public function about(): View
    {
        return view('guest.about');
    }

    /**
     * Display guest contact page.
     */
    public function contact(): View
    {
        return view('guest.contact');
    }

    /**
     * Display guest terms page.
     */
    public function term(): View
    {
        return view('guest.term');
    }

    /**
     * Display guest privacy policy page.
     */
    public function policy(): View
    {
        return view('guest.policy');
    }
}
