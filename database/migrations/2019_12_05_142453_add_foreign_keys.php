<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
          $table->foreign('restaurant_id')->references('id')->on('restaurants');
          $table->foreign('user_id')->references('id')->on('users');
          $table->foreign('basket_id')->references('id')->on('baskets');
          $table->foreign('order_status_id')->references('id')->on('order_statuses');
        });

        Schema::table('restaurants', function(Blueprint $table) {
          $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('menu_groups', function(Blueprint $table) {
          $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });

        Schema::table('menu_items', function(Blueprint $table) {
          $table->foreign('menu_group_id')->references('id')->on('menu_groups')->onDelete('cascade');
          $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });

        Schema::table('menu_options', function(Blueprint $table) {
          $table->foreign('menu_option_group_id')->references('id')->on('menu_option_groups')->onDelete('cascade');
        });

        Schema::table('menu_option_groups', function(Blueprint $table) {
          $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });

        Schema::table('item_flags', function(Blueprint $table) {
          $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });

        Schema::table('ratings', function(Blueprint $table) {
          $table->foreign('user_id')->references('id')->on('users');
          $table->foreign('restaurant_id')->references('id')->on('restaurants');
        });

        Schema::table('menu_item_sizes', function(Blueprint $table) {
          $table->foreign('menu_item_id')->references('id')->on('menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
