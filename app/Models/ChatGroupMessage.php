<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroupMessage extends Model
{
    use HasFactory;

    // public function user(){
    //     return $this->belongsTo(User::class);
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
