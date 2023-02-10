<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockList extends Model
{
    use HasFactory;
    public function Sender(){
        return $this->hasMany(User::class,'receiver_id','id');
    }

    public function Receiver(){
        return $this->hasMany(User::class,'sender_id','id');
    }
}
