<?php

namespace App\GraphQL\Queries;

use App\Models\Activities;

class ActivitiesQuery
{
    public function resolve($root, array $args): array
    {
        return Activities::where('id', $args['id'])->get();
    }
}
