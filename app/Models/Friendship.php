<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friendship extends Model
{
    use HasFactory;
     public function Sender(){
        return $this->hasMany(User::class,'receiver_id','id');
    }

    public function Receiver(){
        return $this->hasMany(User::class,'sender_id','id');
    }
}
