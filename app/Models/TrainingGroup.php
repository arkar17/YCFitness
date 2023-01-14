<?php

namespace App\Models;

use App\Models\TrainingUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingGroup extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class,'training_users')
                    //->withPivot(['user_id','training_group_id'])
                    ->withTimestamps();
    }
    public function trainingUser(){
        return $this->hasMany(TrainingUser::class);
    }

    public function user() {
        return $this->belongsTo(User::class,'trainer_id','id');
    }
}
