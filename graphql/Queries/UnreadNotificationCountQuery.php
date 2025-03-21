<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class UnreadNotificationCountQuery
{
    public function resolve($root, array $args)
    {
        return Auth::user()->notifications()->whereNull('read_at')->count();
    }
}
