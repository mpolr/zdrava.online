<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class FriendsController extends Controller
{
    public function find(): \Illuminate\View\View|View
    {
        return view('friends.find');
    }

    public function requests(): \Illuminate\View\View|View
    {
        return view('friends.requests');
    }
}
