<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriber_followings', function (Blueprint $table) {
            $table->foreignId('subscriber_id')->constrained('users');
            $table->foreignId('following_id')->constrained('users');
            $table->primary(['subscriber_id', 'following_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_followings');
    }
};
