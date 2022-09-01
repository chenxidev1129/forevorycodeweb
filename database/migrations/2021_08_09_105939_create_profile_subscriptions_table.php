<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('profile_id');
            $table->integer('card_id');
            $table->string('subscription_id')->nullable();
            $table->string('stripe_charge_id')->nullable();
            $table->integer('plan_id');
            $table->integer('purchase_plan_id');
            $table->decimal('subscription_price', 8, 2);
            $table->integer('free_trial_days')->nullable();
            $table->dateTime('free_trial_start')->nullable();
            $table->dateTime('free_trial_end')->nullable();    
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();    
            $table->enum('status', ['active', 'inactive', 'expired', 'canceled'])->default('active');
            $table->enum('stripe_status', ['active', 'expired', 'canceled']);
            $table->enum('canceled_by', ['user','admin'])->nullable();
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
        Schema::dropIfExists('profile_subscriptions');
    }
}
