<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Получить всех пользователей, кроме самого себя
     * Получить все id, на кого подписан авторизованный пользователь
     * Пройтись по ним циклом, и проверить есть ли у нас фактические подписки
     * Если есть совпадения, дать ему аттрибут is_following
     */
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();

        $followingsIds = SubscriberFollowing::where('subscriber_id', auth()->id())
            ->get('following_id')
            ->pluck('following_id')
            ->toArray();

        foreach ($users as $user) {
            if (in_array($user->id, $followingsIds)) {
                $user->is_following = true;
            }
        }

        return UserResource::collection($users);
    }

    /**
     * Подгружаем пользователей
     * Цепляем image для N+1
     */
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with('image')
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }

    /**
     * Используем отношение для авторизованного пользователя
     * Либо привязываем либо отвязываем от $user
     * Возвращаем is_following на фронтенд - либо true либо false - для отображения кнопки
     */
    public function toggleFollowing(User $user)
    {
        $response = auth()->user()->followings()->toggle($user->id);
        $data['is_following'] = count($response['attached']) > 0;
        return $data;
    }

    /**
     * Получаем посты от пользователей на которых мы подписаны
     * Получаем посты, только там где user_id совпадает с id подписаннных пользователей, исключаем eager loader через with
     */
    public function followingPost()
    {
        $followingIds = auth()->user()->followings()->get()->pluck('id')->toArray();
        $posts = Post::with('image')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }
}
