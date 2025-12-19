<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriberFollowing extends Model
{
    use HasFactory;
    protected $table = 'subscriber_followings';
    protected $fillable = [
        'subscriber_id',
        'following_id',
    ];
}
