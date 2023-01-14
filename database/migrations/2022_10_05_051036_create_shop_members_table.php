<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_members', function (Blueprint $table) {
            $table->id();
            $table->string('member_type');
            $table->string('duration')->default(0);
            $table->integer('price')->default(0);
            $table->integer('post_count')->default(0);
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
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
        Schema::dropIfExists('shop_members');
    }
}
