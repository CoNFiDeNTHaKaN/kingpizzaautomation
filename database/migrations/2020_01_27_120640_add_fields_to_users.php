<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_card_id')->nullable();
            $table->string('stripe_card_brand')->nullable();
            $table->string('stripe_card_last4')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('delivery_address_line1')->nullable();
            $table->string('delivery_address_line2')->nullable();
            $table->string('delivery_address_city')->nullable();
            $table->string('delivery_address_county')->nullable();
            $table->string('delivery_address_postcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Users', function (Blueprint $table) {
            //
        });
    }
}
