<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
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

            PostImage::clearStorageForUser(auth()->id());

            $post->load('image');
            return PostResource::make($post);
        } catch (\Throwable $exception) {
            DB::rollBack();
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
