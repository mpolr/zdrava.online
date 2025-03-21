<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscribeConfirmMutation
{
    public function resolve($root, array $args): array
    {
        $user = Auth::user();
        $subscriber = User::findOrFail($args['id']);

        $user->confirmedSubscribers()->attach($subscriber->id);
        $subscriber->confirmedSubscriptions()->attach($user->id);

        return ['success' => true, 'message' => 'Подписка подтверждена'];
    }
}
