<?php

namespace App\GraphQL\Queries;

use App\Models\Activities;
use Illuminate\Support\Facades\Auth;

class FeedQuery
{
    public function resolve($root, array $args)
    {
        $user = Auth::user();
        return Activities::whereIn('user_id', $user->confirmedSubscriptions()->pluck('users.id'))->latest()->get();
    }
}
