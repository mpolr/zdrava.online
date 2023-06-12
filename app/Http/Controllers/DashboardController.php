<?php

namespace App\Http\Controllers;

use App\Models\Activities;
use App\Models\Subscription;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): \Illuminate\View\View|View
    {
        $activities = Activities::where('user_id', auth()->user()->id)
                ->orWhereIn('user_id', Subscription::select(['user_id'])
                ->where('subscriber_id', auth()->user()->id)
                ->where('confirmed', 1))
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('dashboard', [
            'activities' => $activities,
        ]);
    }
}
