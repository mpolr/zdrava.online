<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function find(): Factory|View
    {
        return view('friends.find');
    }

    public function requests(): Factory|View
    {
        return view('friends.requests');
    }
}
