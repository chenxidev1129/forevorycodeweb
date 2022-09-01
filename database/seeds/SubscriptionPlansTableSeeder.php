<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subscription_plans')->insert([
            [
                'plan' => 'Free Trial',
                'price' => '0',
                'days' => '30',
                'slug' => 'free_trial',
                'stripe_price_id' => Null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan' => 'Monthly',
                'price' => '800',
                'days' => '30',
                'slug' => 'month',
                'stripe_price_id' => 'price_1JKMS7SDqWMVfKtCcYkB79fA',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan' => 'Annual',
                'price' => '1000',
                'days' => '365',
                'slug' => 'annual',
                'stripe_price_id' => 'price_1JKceTSDqWMVfKtCJPO0ozva',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plan' => 'Legacy',
                'price' => '1000',
                'days' => 'Lifetime',
                'slug' => 'life_time',
                'stripe_price_id' => Null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
