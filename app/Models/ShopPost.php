<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShopPost extends Model
{
    use HasFactory,SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_react_shopposts()
    {
        return $this->hasMany(UserReactShoppost::class, 'post_id', 'id');
    }

    public function user_saved_shopposts()
    {
        return $this->hasMany(UserSavedPost::class, 'post_id', 'id');
    }
}
