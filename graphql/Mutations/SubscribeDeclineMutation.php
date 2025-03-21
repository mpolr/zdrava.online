<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SubscribeDeclineMutation
{
    public function resolve($root, array $args): array
    {
        $user = Auth::user();
        $subscriber = User::findOrFail($args['id']);

        $user->subscriptions()->detach($subscriber->id);

        return ['success' => true, 'message' => 'Запрос на подписку отклонен'];
    }
}
