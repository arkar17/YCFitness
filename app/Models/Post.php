<?php

namespace App\Models;

use App\Models\User;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function user_reacted_posts()
    {
        return $this->hasMany(UserReactPost::class, 'post_id', 'id');
    }

    public function user_saved_posts()
    {
        return $this->hasMany(UserSavedPost::class, 'post_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'post_id', 'id');
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($post) {
            $post->user_saved_posts()->each(function($savepost) {
                $savepost->delete();
            });
            $post->user_reacted_posts()->each(function($reactpost) {
                $reactpost->delete();
            });
        });

    }
}
