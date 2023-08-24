<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AthleteController extends Controller
{
    public function training(): \Illuminate\View\View|View
    {
        return view('athlete.training', [
            'activities' => Auth::user()->activities()->orderBy('created_at', 'desc')->limit(100)->get()
        ]);
    }

    public function subscribers(?int $userId = null): \Illuminate\View\View|View
    {
        if (empty($userId)) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($userId);
        }

        return view('athlete.subscribers', [
            'user' => $user,
        ]);
    }

    public function subscriptions(?int $userId = null): \Illuminate\View\View|View
    {
        if (empty($userId)) {
            $user = Auth::user();
        } else {
            $user = User::findOrFail($userId);
        }

        return view('athlete.subscriptions', [
            'user' => $user,
        ]);
    }
}
