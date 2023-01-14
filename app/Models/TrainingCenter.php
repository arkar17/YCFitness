<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCenter extends Model
{
    use HasFactory;
    protected $fillable = ['meal_plan_id','workout_plan_id','trainer_id','member_id','training_group_id','user_id'];
}
