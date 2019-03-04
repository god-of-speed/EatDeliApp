<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('oldPrice');
            $table->string('price');
            $table->string('currency');
            $table->boolean('isSet');
            $table->integer('domain_id')->unsigned()->foreign('domain_id')->references('id')->on('domains');
            $table->integer('menu_id')->nullable()->unsigned()->foreign('menu_id')->references('id')->on('menus');
            $table->integer('menuClass_id')->nullable()->unsigned()->foreign('menuClass_id')->references('id')->on('menuclasses');
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
        Schema::dropIfExists('products');
    }
}
