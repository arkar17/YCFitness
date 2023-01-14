<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopmemberHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'shopmember_type_id', 'date'];
}
