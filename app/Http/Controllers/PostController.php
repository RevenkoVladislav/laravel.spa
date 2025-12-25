<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\CommentRequest;
use App\Http\Requests\Post\RepostRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Post\PostResource;
use App\Models\Comment;
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
     * Берем все посты из БД + картинки + счетчик лайков пользователя, где автор это авторизованный пользователь
     * Обращаемся к сервису, в который передаем все посты, и id авторизованного пользователя
     * Получаем posts которые имеют лайки
     * Отдаем их в виде PostResource
     */
    public function index()
    {
        $posts = Post::with(['image', 'repostedPost'])
            ->withCount(['likedUsers', 'repostedByPosts'])
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
     * Просчитываем количество лайков
     * возвращаем is_liked для фронта
     */
    public function toggleLike(Post $post)
    {
        $response = auth()->user()->likedPosts()->toggle($post);
        $data['is_liked'] = count($response['attached']) > 0;
        $data['likes_count'] = $post->likedUsers()->count();
        return $data;
    }

    /**
     * метод для репоста записи
     *
     * Заполняем data данными о пользователе и id post
     * создаем запись
     * возвращаем посчитанное количество репоста для отображения на фронте
     */
    public function repost(RepostRequest $request, Post $post)
    {
        //Если нужно запретить репост репоста, то используем этот код.
//        if ($post->reposted_id !== null) {
//            abort(403, 'You cannot repost a repost');
//        }

        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['reposted_id'] = $post->id;

        Post::create($data);

        return response()->json([
            'reposted_count' => $post->repostedByPosts()->count(),
        ]);
    }

    /**
     * Метод по сохранению коммента
     * Валидация данных
     * Добавление в data данных о id поста и пользователя
     * Сохраняем в бд
     * Возвращаем CommentResource
     */
    public function storeComment(CommentRequest $request, Post $post)
    {
        $data = $request->validated();
        $data['post_id'] = $post->id;
        $data['user_id'] = auth()->id();

        $comment = Comment::create($data);

        return CommentResource::make($comment);
    }
}
