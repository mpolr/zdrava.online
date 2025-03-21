<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class SubscriptionsQuery
{
    public function resolve($root, array $args)
    {
        return User::findOrFail($args['id'])->confirmedSubscriptions;
    }
}
