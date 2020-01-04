<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InventoryCountDetails extends Migration
{
    public function up()
    {
        Schema::create('inventory_count_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invCountCode');
            $table->string('invCode');
            $table->decimal('invQty',10,2);
            $table->decimal('newInvQty',10,2);
             $table->decimal('qtyDiscrepancy',10,2);
            $table->integer('userId');
            $table->timestamps();
        });
    }

    public function down()
    {
        //
    }
}
