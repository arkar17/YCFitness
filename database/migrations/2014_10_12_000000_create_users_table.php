<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('bio')->nullable();
            $table->string('member_type');
            $table->integer('request_type');
            $table->string('membertype_level');

            $table->integer('shopmember_type_id')->default(0);
            $table->integer('shop_request')->default(0);
            $table->integer('shop_post_count')->default(0);
            $table->date('shopfrom_date')->nullable();
            $table->date('shopto_date')->nullable();

            $table->string('member_code')->nullable();
            $table->boolean('ingroup')->default(0);
            $table->integer('height')->default(0);
            $table->float('weight')->default(0.0);
            $table->float('ideal_weight')->default(0.0);
            $table->string('bad_habits')->nullable();
            $table->string('most_attention_areas')->nullable();
            $table->string('average_night')->nullable();
            $table->string('physical_activity')->nullable();
            $table->string('diet_type')->nullable();
            $table->string('daily_life')->nullable();
            $table->string('energy_level')->nullable();
            $table->string('body_type')->nullable();
            $table->string('physical_limitation')->nullable();
            $table->string('age')->nullable();
            $table->string('goal')->nullable();
            $table->string('activities')->nullable();
            $table->float('bmi')->default(0.0);
            $table->float('bmr')->default(0.0);
            $table->float('bfp')->default(0.0);
            $table->string('gender')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->boolean('active_status')->nullable();
            // $table->string('current_member_type')->nullable();
            $table->float('neck')->default(0.0);
            $table->float('waist')->default(0.0);
            $table->float('hip')->default(0.0);
            $table->float('shoulders')->default(0.0);
            $table->string('password');
            $table->string('hydration')->nullable();

            // for trainer crud
            $table->string('training_type')->nullable();
            $table->integer('profile_id')->nullable();
            $table->integer('cover_id')->nullable();
            $table->integer('chat_id')->default(0);
            $table->integer('message_id')->default(0);
            $table->datatime('last_seen')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
