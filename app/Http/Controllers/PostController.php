<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\LikedPost;
use App\Models\Post;
use App\Models\PostImage;
use App\Services\PostService;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Подключение сервиса через DI
     */
    public function __construct(
        protected PostService $postService
    )
    {
    }

    /**
     * Берем все посты из БД, где автор это авторизованный пользователь
     * Обращаемся к сервису, в который передаем все посты, и id авторизованного пользователя
     * Получаем posts которые имеют лайки
     * Отдаем их в виде PostResource
     */
    public function index()
    {
        $posts = Post::with('image')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $posts = $this->postService->likedPosts($posts, auth()->id());

        return PostResource::collection($posts);
    }

    /**
     * прокидываем в сервис валидированные данные и id авторизованного пользователя
     * очищаем картинки
     * подгружаем image для жадной загрузки и оптимизации запросов в бд
     * вощвращаем PostResource
     */
    public function store(StoreRequest $request)
    {
        $post = $this->postService->store(
            $request->validated(),
            auth()->id(),
        );

        $this->postService->clearUnusedImages(auth()->id());

        $post->load('image');

        return PostResource::make($post);
    }

    /**
     * Используем отношение для авторизованного пользователя
     * привязываем и отвязываем $post
     * возвращаем is_liked для фронта
     */
    public function toggleLike(Post $post)
    {
        $response = auth()->user()->likedPosts()->toggle($post);
        $data['is_liked'] = count($response['attached']) > 0;
        return $data;
    }
}
