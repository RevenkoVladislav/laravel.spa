<?php

namespace App\Services;

use App\Models\SubscriberFollowing;

class UserService
{
    public function __construct(
        protected PostService $postService,
    ) {}

    /**
     * Доступ к методу получения постов
     */
    public function markLikedPosts($posts, $authUser)
    {
        return $this->postService->likedPosts($posts, $authUser);
    }

    /**
     *  Получить все id, на кого подписан авторизованный пользователь
     *  Пройтись по ним циклом, и проверить есть ли у нас фактические подписки
     *  Если есть совпадения, дать ему аттрибут is_following
     */
    public function getFollowers($users, $authUser)
    {
        $followingsIds = SubscriberFollowing::where('subscriber_id', $authUser)
            ->get('following_id')
            ->pluck('following_id')
            ->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingsIds)) {
                $user->is_following = true;
            }
        }

        return $users;
    }
}
