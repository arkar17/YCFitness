<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatGroup extends Model
{
    use HasFactory;

    protected $fillable = ['group_name','group_owner_id'];

    public function user(){
        return $this->belongsTo(User::class,'group_owner_id','id');
     }
}
