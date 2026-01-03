<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StatsRequest;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\User\UserResource;
use App\Models\LikedPost;
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
     * Цепляем image и repostedPost для N+1
     * получаем лайки на постах через сервис
     */
    public function show(User $user)
    {
        $posts = $user->posts()
            ->with(['image', 'repostedPost', 'user'])
            ->withCount(['likedUsers', 'repostedByPosts', 'comments'])
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
     * Реализуем механику - после лайка, пост больше не отображается в Feed странице
     * Получаем id постов с лайком от пользователя
     * Получаем посты, только там где user_id совпадает с id подписаннных пользователей,
     * И НЕ совпадают с пролайканными постами
     * исключаем eager loader через with
     */
    public function followingUnlikedPost()
    {
        $followingIds = auth()->user()->followings()->get()->pluck('id')->toArray();

        $likedPostIds = LikedPost::where('user_id', auth()->id())
            ->get('post_id')
            ->pluck('post_id')
            ->toArray();

        $posts = Post::with(['image', 'repostedPost', 'user'])
            ->withCount(['likedUsers', 'repostedByPosts', 'comments'])
            ->whereIn('user_id', $followingIds)
            ->whereNotIn('id', $likedPostIds)
            ->latest()
            ->get();

        return PostResource::collection($posts);
    }

    /**
     * Метод получения пролайканных постов
     * Формируем посты, для авторизованного пользователя - отношение likedPosts
     * Вместе с картинкой (N + 1)
     * Вместе со счетчиком лайков на посте
     * Возвращаем PostResource
     */
    public function likedPosts()
    {
        $posts = auth()->user()
            ->likedPosts()
            ->with(['image', 'repostedPost', 'user'])
            ->withCount(['likedUsers', 'repostedByPosts', 'comments'])
            ->latest()
            ->get();

        $posts = $this->userService->markLikedPosts($posts, auth()->id());

        return PostResource::collection($posts);
    }

    /**
     * Метод для получения статистики для пользователя
     * Формируем userId из request берем значение user_id если он не пустое и не null
     * иначе берем данные по авторизованному пользователю
     * Обращаемся к userService и получаем массив со статистикой
     * Возвращаем сформированные данные
     */
    public function getStats(StatsRequest $request)
    {
        $data = $request->validated();

        $userId = $request->filled('user_id')
            ? $request->input('user_id')
            : auth()->id();

        $stats = $this->userService->getStatistics($userId);

        return response()->json(['data' => $stats]);
    }
}
