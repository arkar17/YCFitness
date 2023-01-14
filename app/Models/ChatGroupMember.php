<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatGroupMember extends Model
{
    use HasFactory;

    protected $fillable = ['group_id','member_id'];

    public function user(){
       return $this->belongsTo(User::class,'member_id','id');
    }
}
