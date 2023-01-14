<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;
    protected $fillable = ['workout_plan_type','member_type','workout_name','gender_type','time','calories','workout_level','workout_periods','place','day','image','video'];


    public function workoutPlan() {
        return $this->belongsTo(WorkoutPlan::class,'workout_plan_id', 'id');
    }

    public function personalworkoutinfos()
    {
        return $this->hasMany(PersonalWorkOutInfo::class, 'workout_id', 'id');
    }
}
