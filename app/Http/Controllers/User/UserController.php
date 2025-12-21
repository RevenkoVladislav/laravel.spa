<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\Post;
use App\Models\SubscriberFollowing;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
    ) {}

    /**
     * Получить всех пользователей, кроме самого себя
     * Получить данные о подписках
     */
    public function index()
    {
        $users = User::whereNot('id', auth()->id())->get();

        $users = $this->userService->getFollowers($users, auth()->id());

        return UserResource::collection($users);
    }

    /**
     * Подгружаем посты от пользователя
     * Цепляем image для N+1
     * получаем лайки на постах через сервис
     */
    public function show(User $user)
    {
        $posts = $user->posts()
            ->withCount('likedUsers')
            ->with('image')
            ->latest()
            ->get();

        $posts = $this->userService->markLikedPosts($posts, auth()->id());

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
     * Получаем лайки на постах через сервис
     */
    public function followingPost()
    {
        $followingIds = auth()->user()->followings()->get()->pluck('id')->toArray();
        $posts = Post::with('image')
            ->withCount('likedUsers')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->get();

        $posts = $this->userService->markLikedPosts($posts, auth()->id());

        return PostResource::collection($posts);
    }
}
