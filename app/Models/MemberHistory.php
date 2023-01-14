<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberHistory extends Model
{
    use HasFactory;
    protected $guraded=[];
    protected $fillable = ['user_id','member_id','member_type_level','date'];
}
