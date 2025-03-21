<?php

namespace App\GraphQL\Queries;

use App\Models\User;

class SearchAthletesQuery
{
    public function resolve($root, array $args): array
    {
        return User::where('first_name', 'like', "%{$args['query']}%")
            ->orWhere('last_name', 'like', "%{$args['query']}%")
            ->orWhere('nickname', 'like', "%{$args['query']}%")
            ->get();
    }
}
