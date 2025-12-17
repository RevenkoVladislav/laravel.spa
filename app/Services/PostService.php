<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;

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
}
