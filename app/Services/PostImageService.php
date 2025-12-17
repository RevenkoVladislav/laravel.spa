<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\Storage;

class PostImageService
{
    /**
     * Вспомогательный метод для создания постов.
     * Если к посту прикрепляли картинку, то ищем картинку в таблице PostImage
     * Используем фильтрацию где проверяем что картинку применил данный пользователь
     * обновляем статус и post_id у картинки
     */
    public function imageToPost(Post $post, ?int $imageId, int $userId): void
    {
        if (!$imageId) {
            return;
        }

        $image = PostImage::where('id', $imageId)
            ->where('user_id', $userId)
            ->where('status', false)
            ->firstOrFail();

        $image->update([
            'status' => true,
            'post_id' => $post->id,
        ]);
    }

    /**
     * проводим очистку неопубликованных картинок для конкретного авторизованного пользователя
     */
    public function clearStorageForUser(int $userId): void
    {
        $images = PostImage::where('user_id', $userId)
            ->where('status', false)
            ->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}
