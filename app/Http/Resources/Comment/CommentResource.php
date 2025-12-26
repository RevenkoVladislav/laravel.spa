<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parent = $this->parent;
        return [
            'id' => $this->id,
            'body' => $this->body,
            'date' => $this->created_at->diffForHumans(),
            'user' => new UserResource($this->user),
            'reply_for_user' => $parent ? $parent->user->name : null,
            'parent_id' => $parent ? $parent->id : null,
            'parent_body' => $parent ? Str::limit($parent->body, 50) : null,
        ];
    }
}
