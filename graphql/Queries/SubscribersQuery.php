<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class SubscribersQuery
{
    public function resolve($root, array $args)
    {
        return User::findOrFail($args['id'])->confirmedSubscribers;
    }
}
