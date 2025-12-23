<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => $this->image?->url, //защита от null error т.к пост может быть без картинки
            'date' => $this->date, //аттрибут date в модели Post
            'is_liked' => $this->is_liked ?? false,
            'likes_count' => $this->liked_users_count,
            'reposted_post' => new RepostedPostResource($this->repostedPost),
            'reposted_count' => $this->reposted_by_posts_count,
        ];
    }
}
