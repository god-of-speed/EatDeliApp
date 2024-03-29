<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('domain_id')->unsigned()->foreign('domain_id')->reference('id')->on('domains');
            $table->integer('product_id')->unsigned()->foreign('product_id')->reference('id')->on('products');
            $table->integer('buyer_id')->unsigned()->foreign('buyer_id')->reference('id')->on('users');
            $table->string('status');
            $table->boolean('paid');
            $table->boolean('delivered');
            $table->boolean('received');
            $table->integer('quantity');
            $table->integer('price');
            $table->softDeletes();
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
        Schema::dropIfExists('orders');
    }
}
