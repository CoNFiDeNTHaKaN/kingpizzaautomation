<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('restaurant_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('basket_id')->unsigned();
            $table->dateTime('desired_time');
            $table->dateTime('predicted_time')->nullable();
            $table->dateTime('dispatched_time')->nullable();
            $table->string('payment_type');
            $table->boolean('paid')->default(0);
            $table->string('payment_id')->nullable();
            $table->boolean('collection')->default(0);

            $table->string('delivery_line1')->nullable();
            $table->string('delivery_line2')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_county')->nullable();
            $table->string('delivery_postcode')->nullable();

            $table->text('notes')->nullable();
            $table->bigInteger('order_status_id')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
