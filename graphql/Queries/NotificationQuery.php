<?php

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Auth;

class NotificationQuery
{
    public function resolve($root, array $args)
    {
        return Auth::user()->notifications()->latest()->get();
    }
}
