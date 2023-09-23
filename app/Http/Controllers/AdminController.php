<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminController extends Controller
{
    public function index(): \Illuminate\View\View|View
    {
        if (!\auth()->user()->hasRole('admin')) {
            abort(403, 'Insufficient access rights');
        }

        return view('admin.index', [
            'users' => User::all(),
        ]);
    }
}
