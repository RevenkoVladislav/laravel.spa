<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StoreRequest;

class PostController extends Controller
{
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

    }
}
