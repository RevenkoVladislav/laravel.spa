<?php

namespace App\Console\Commands;

use App\Models\PostImage;
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
    public function handle()
    {
        $cutTime = Carbon::now()->subHour();
        $images = PostImage::where('status', false)
            ->where('created_at', '<', $cutTime)
            ->get();

        $count = $images->count();

        if ($count > 0) {
            $this->info("Found $count images for deleted");
        } else {
            $this->info("No images for deleted");
            return 0;
        }

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $this->info("Deleted $count images");
        return 0;
    }
}
