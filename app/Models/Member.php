<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Member extends Model
{
    use HasFactory,HasRoles;

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id', 'id');
    // }

    public function mealplan()
    {
        return $this->hasOne(MealPlan::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'member_histories')
                    ->withPivot(['date','from_member_id','to_member_id','member_type_level','deleted_at'])
                    ->withTimestamps();
    }

    // public static function boot() {
    //     parent::boot();
    //     self::deleting(function($member) {
    //         $member->mealplan()->each(function($mealplan) {
    //             $mealplan->meal()->each(function($meal){
    //                 $meal->delete();
    //             });
    //             $mealplan->delete();
    //         });
    //     });
    // }
}
