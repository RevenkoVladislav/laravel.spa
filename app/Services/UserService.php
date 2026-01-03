<?php

namespace App\Services;

use App\Models\LikedPost;
use App\Models\Post;
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

    /**
     * Метод для формирования статистических данных
     * Формируем массив
     * Берем количество пользователей которые подписаны на меня
     * Берем количество пользователей на которых подписан я
     * Берем id постов которые создал данный пользователь, и считаем сколько у них лайков
     * Считаем количество постов созданных юзером
     * Возвращаем сформированные данные
     */
    public function getStatistics($userId)
    {
        $stats = [];
        $stats['subscribers_count'] = SubscriberFollowing::where('following_id', $userId)->count();
        $stats['followings_count'] = SubscriberFollowing::where('subscriber_id', $userId)->count();

        $postIds = Post::where('user_id', $userId)->pluck('id')->toArray();
        $stats['likes_count'] = LikedPost::whereIn('post_id', $postIds)->count();
        $stats['posts_count'] = count($postIds);

        return $stats;
    }
}
