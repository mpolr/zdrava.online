<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscribeMutation
{
    public function resolve($root, array $args): array
    {
        $user = Auth::user();
        $targetUser = User::findOrFail($args['id']);

        if ($user->confirmedSubscriptions()->where('users.id', $targetUser->id)->exists()) {
            return ['success' => false, 'message' => 'Вы уже подписаны'];
        }

        $user->subscriptions()->attach($targetUser->id);

        return ['success' => true, 'message' => 'Запрос на подписку отправлен'];
    }
}
