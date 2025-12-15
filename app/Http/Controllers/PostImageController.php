<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\StoreRequest;
use App\Http\Resources\Post\PostImageResource;
use App\Models\PostImage;

class PostImageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');
        $image = PostImage::create([
            'path' => $path,
            'user_id' => auth()->id(),
            'status' => false,
        ]);
        return PostImageResource::make($image);
    }
}
