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
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('user_type', ['user', 'admin','administrator','support'])->default('user');
            $table->string('email')->unique();
            $table->string('country_code')->nullable();
            $table->string('country_iso_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('image')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->enum('email_verified', ['0','1'])->default('0');
            $table->string('password')->nullable();
            $table->bigInteger('otp')->nullable();
            $table->string('verify_token')->nullable();
            $table->string('facebook_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('apple_id')->nullable();
            $table->enum('login_type', ['forevory', 'facebook','google','apple'])->default('forevory');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->rememberToken();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 10, 8)->nullable();
            $table->string('country_short_name')->nullable();
            $table->string('customer_id')->nullable();
            $table->enum('profile_status', ['0', '1'])->default('0');
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
