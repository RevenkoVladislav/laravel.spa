<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;

    protected $table = 'post_images';
    protected $fillable = [
        'path',
        'status',
        'post_id',
        'user_id'
    ];

    public function getUrlAttribute(): string
    {
        return url('storage/' . $this['path']);
    }

    public static function clearStorageForUser(int $userId): void
    {
        $images = PostImage::where('user_id', $userId)->where('status', false)->get();

        foreach ($images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }
}
