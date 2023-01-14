<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalMealInfo extends Model
{
    use HasFactory;

    public function meal()
    {
        return $this->belongsTo(Meal::class, 'meal_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}
