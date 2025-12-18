<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
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
     * Отдаем их в виде PostResource
     */
    public function index()
    {
        $posts = Post::with('image')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
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
}
