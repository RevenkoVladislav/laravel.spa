<?php

namespace App\Services;

use App\Models\LikedPost;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Ramsey\Collection\Collection;

class PostService
{
    public function __construct(
        protected PostImageService $postImageService
    ) {}

    /**
     * @data - валидированные данные из request
     * @userId - id авторизованного пользователя
     * Отсекаем из data поле image_id т.к в таблице Post его нет
     * в @data записываем id авторизованного пользователя
     * Создаем post передав ему data
     * Используем метод из сервиса управления картинками и прикрепляем картинки к посту
     * (проверка существования картинок происходит внутри метода)
     * возвращаем $post
     */
    public function store(array $data, int $userId): Post
    {
        return DB::transaction(function () use ($data, $userId) {

            $imageId = $data['image_id'] ?? null;
            unset($data['image_id']);

            $data['user_id'] = $userId;

            $post = Post::create($data);

            $this->postImageService->imageToPost($post, $imageId, $userId);

            return $post;
        });
    }

    /**
     * шорткат для очищения картинок из сервиса по управлению картинками
     */
    public function clearUnusedImages(int $userId): void
    {
        $this->postImageService->clearStorageForUser($userId);
    }

    /**
     * Получаем все пролайканный посты для авторизованного пользователя
     * Берем значения post_id и делаем массив из этих значений
     *
     * Проходимся по постам в цикле
     * если id поста совпадает с id пролайканных постов то выставляем is_liked = true
     * возвращаем все измененные посты
     */
    public function likedPosts($posts, $authUser)
    {
        $likedPostIds = LikedPost::where('user_id', $authUser)
            ->get('post_id')
            ->pluck('post_id')
            ->toArray();

        foreach ($posts as $post) {
            if (in_array($post->id, $likedPostIds)) {
                $post->is_liked = true;
            }
        }
        return $posts;
    }
}
