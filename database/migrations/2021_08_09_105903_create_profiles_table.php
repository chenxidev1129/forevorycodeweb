<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('profile_name')->default('Ralph “Ralphy” Sarris');
            $table->date('date_of_birth')->default('1950-08-10');
            $table->date('date_of_death')->default('1950-08-10');
            $table->string('short_description')->default('Best Brother');
            $table->string('profile_image')->default('ralph.png');
            $table->string('banner_image')->default('profile-banner.jpg');
            $table->longText('journey')->default('<p>Our beloved Ralph Sarris, age 70, resident of Austin, was born into Eternal Life on Thursday, October 29, 2020. He is reunited with his parents, Raymond and Sally Gomez Sarris; his brother, Donald Sarris his sister, Roseanna Sarris. Ralph is survived by his son, grandsons, and grandaugthers. </p> <p>Ralph was born in Brooklyn, New York, to Greek immigrant parents, Themis (née Katavolos) and George Andrew Sarris, and grew up in Ozone Park, Queens.[2] After attending John Adams High School in South Ozone Park (where he overlapped with Jimmy Breslin), he graduated from Columbia University in 1951 and then served for three years in the Army Signal Corps before moving to Paris for a year, where he befriended Jean-Luc Godard and François Truffaut. Upon returning to New Yorks Lower East Side, Sarris briefly pursued graduate studies at his alma mater and Teachers College, Columbia University before turning to film criticism as a vocation.</p>');
            $table->enum('status', ['inactive', 'active', 'expired'])->default('active');
            $table->text('terms_condition')->nullable();
            $table->enum('is_saved', ['0','1'])->default('0');
            $table->string('qrcode_image')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->longText('family_tree')->nullable();
            $table->string('shared_link')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
