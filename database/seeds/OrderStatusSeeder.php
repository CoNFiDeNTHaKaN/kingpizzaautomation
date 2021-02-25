<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->insert([[
          "name" => "unpaid_and_unconfirmed",
        ],[
          "name" => "paid_and_unconfirmed",
        ],[
          "name" => "unpaid_and_confirmed",
        ],[
          "name" => "paid_and_confirmed",
        ],[
          "name" => "out_for_delivery",
        ],[
          "name" => "delivered",
        ],[
          "name" => "cancelled",
        ],[
          "name" => "refunded",
        ]]);
    }
}
