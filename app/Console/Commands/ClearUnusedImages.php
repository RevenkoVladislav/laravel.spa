<?php

namespace App\Console\Commands;

use App\Models\PostImage;
use App\Services\PostImageService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearUnusedImages extends Command
{
    protected $signature = 'images:clear-unused';

    protected $description = 'Deletes unused images older than 1 hours';

    /**
     * определяем срок существования картиник для удаления
     * Ищем все зависшие картинки (status = false)
     * Если нечего удалять то return
     * Удаляем их из Storage и БД
     * в случае удаления возвращаем код 0 - успех
     *
     */
    public function handle(PostImageService $postImageService): int
    {
        $cutTime = Carbon::now()->subHour();
        $images = PostImage::where('status', false)
            ->where('created_at', '<', $cutTime)
            ->get();

        if ($images->isEmpty()) {
            $this->info("No images for deleted");
            return self::SUCCESS;
        }

        $count = $images->count();

        $postImageService->clearStorage($images);

        $this->info("Deleted $count images");
        return self::SUCCESS;
    }
}
