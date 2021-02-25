<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->timestamps();
            $table->string('name');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->longText('allergy_info')->nullable();
            $table->integer('cover_image')->nullable();
            $table->integer('logo')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('contact_number');
            $table->json('opening_hours');
            $table->json('order_hours');
            $table->json('delivery_hours');
            $table->integer('hygiene_rating')->nullable();
            $table->integer('collection_lead_time')->nullable();
            $table->integer('delivery_lead_time')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('address_city');
            $table->string('address_county');
            $table->string('address_postcode');
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->decimal('delivery_range', 5, 2)->default(4);
            $table->decimal('delivery_minimum', 6, 2);
            $table->decimal('delivery_fee', 6, 2);
            $table->integer('discount_percentage')->nullable();
            $table->decimal('service_charge',4,2)->nullable();
            $table->text('flags')->nullable();
            $table->text('favourites')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
