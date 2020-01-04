<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GoodsReturnDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_return_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gdsReturnCode');
            $table->string('invCode');
            $table->string('invName');
            $table->date('storeDays');
            $table->decimal('qty',10,2);
            $table->string('uom');  
            $table->decimal('price',10,2);    
            $table->integer('userId');
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
        //
    }
}
