<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('email', 100);
            $table->string('password', 200);
            $table->string('name', 200);
            $table->date('birthdate');
            $table->string('city',100)->nullable();
            $table->string('work', 100)->nullable();
            $table->string('avatar',100)->default('default.jpg');
            $table->string('cover',100)->default('cover.jpg');
            $table->string('token',200)->nullable();
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
};
