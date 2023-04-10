<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\Post;
use App\Models\Payment;
use App\Models\Friendship;
use App\Models\TrainingUser;
use App\Models\TrainingGroup;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,HasRoles, HasFactory, Notifiable;


    // protected $fillable = [
    //     'name',
    //     'email',
    //     'phone',
    //     'password',
    // ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $appends = ['online'];

    public function getOnlineAttribute()
    {
        $lastSeenAt = $this->last_seen;
        $currentTime = now();
        $onlineThreshold = config('app.online_threshold', 5); // in minutes
    
        return $lastSeenAt && $lastSeenAt->diffInMinutes($currentTime) <= $onlineThreshold;
    }
    // public function member_histories()
    // {
    //     return $this->hasMany(MemberHistory::class);
    // }

    public function user_reacted_posts()
    {
        return $this->hasMany(UserReactPost::class, 'user_id', 'id');
    }

    public function user_reacted_comments()
    {
        return $this->hasMany(UserReactComment::class, 'user_id', 'id');
    }

    public function user_saved_posts()
    {
        return $this->hasMany(UserSavedPost::class, 'user_id', 'id');
    }

    public function user_saved_shopposts()
    {
        return $this->hasMany(UserSavedShoppost::class, 'user_id', 'id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_histories')
                    ->withPivot(['date','to_member_id','from_member_id','member_type_level','deleted_at'])
                    ->withTimestamps();
    }

    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'id');
    }

    public function tainer_groups()
    {
        return $this->belongsToMany(TrainingGroup::class,'training_users')
                    // ->withPivot(['user_id','training_group_id'])
                    ->withTimestamps();
    }

    public function payment(){
        return $this->hasOne(Payment::class);
    }

    public function watertracks(){
        return $this->hasMany(WaterTracked::class,'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function shopposts()
    {
        return $this->hasMany(ShopPost::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function personalmealinfos()
    {
        return $this->hasMany(PersonalMealInfo::class, 'client_id', 'id');
    }

    public function personalworkoutinfos()
    {
        return $this->hasMany(PersonalWorkOutInfo::class, 'user_id', 'id');
    }
    public function reports()
    {
        return $this->hasMany(Report::class, 'user_id', 'id');
    }

    public function trainingUser(){
        return $this->hasMany(TrainingUser::class,'user_id','id');
    }

    public function post(){
        return $this->hasOne(Post::class);
    }
    public function notifri(){
        return $this->hasMany(Notification::class,'receiver_id');
    }
    public function friendship(){
    return $this->belongsTo(Friendship::class);
    }

    public function user_profile(){
        return $this->belongsTo(Profile::class,'profile_id','id');
    }
    // public function Sender(){
    //     return $this->hasMany(Friendship::class,'receiver_id');
    // }

    // public function Receiver(){
    //     return $this->hasMany(Friendship::class,'sender_id');
    // }


//   public function member()
//   {
//       return $this->hasOne(User::class);
//   }
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $guarded = [];
}
