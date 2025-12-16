<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Валидируем данные, оборачиваем операцию в транзакцию
     * Отсекаем из data поле image_id т.к в таблице Post его нет
     * Создаем post передав ему data
     * Используем вспомогательный метод для привязки картинки к посту
     * Возвращаем PostResource и для модели $post прогружаем отношение image
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $imageId = $data['image_id'];
            unset($data['image_id']);

            $data['user_id'] = auth()->id();
            $post = Post::create($data);

            $this->imageToPost($post, $imageId);

            DB::commit();

            $post->load('image');
            return PostResource::make($post);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Вспомогательный метод для создания постов.
     * Если к посту прикрепляли картинку, то ищем картинку в таблице PostImage
     * Используем фильтрацию где проверяем что картинку применил данный пользователь
     * обновляем статус и post_id у картинки
     */
    private function imageToPost(Post $post, ?int $imageId): void
    {
        if (!$imageId) {
            return;
        }

        $image = PostImage::where('id', $imageId)
            ->where('user_id', auth()->id())
            ->where('status', false)
            ->firstOrFail();

        $image->update([
            'status' => true,
            'post_id' => $post->id,
        ]);
    }
}
