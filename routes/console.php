<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('images:clear-unused')->hourly();
//чтобы увидеть сообщения напрямую нужно использовать метод withoutOverLapping или sendOutputTo
