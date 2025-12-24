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
        Schema::table('liked_posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);

            $table->foreignId('user_id')->change()->constrained('users')->cascadeOnDelete();
            $table->foreignId('post_id')->change()->constrained('posts')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('liked_posts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['post_id']);

            $table->foreignId('user_id')->change()->constrained('users');
            $table->foreignId('post_id')->change()->constrained('posts');
        });
    }
};
