<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('images:clear-unused')->everyMinute();
//чтобы увидеть сообщения напрямую нужно использовать метод wsendOutputTo(storage_path('logs/images.log'))
