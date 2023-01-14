<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;


    public function meal_plans()
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan_id', 'id');
    }


    public function personalmealinfos()
    {
        return $this->hasMany(PersonalMealInfo::class, 'meal_id', 'id');
    }


}
