<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('product_order')) {
            Schema::create('product_order', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('product_id');
                $table->bigInteger('order_id');
                $table->integer('quantity');
                $table->integer('price');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('product_order')) {
            Schema::dropIfExists('product_order');
        }
    }
}
