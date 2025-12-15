<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImage\StoreRequest;
use App\Models\PostImage;

class PostImageController extends Controller
{
    public function store(StoreRequest $request)
    {
        $path = $request->file('image')->store('images', 'public');
        PostImage::create([
            'path' => $path,
            'user_id' => auth()->id(),
        ]);
    }
}
