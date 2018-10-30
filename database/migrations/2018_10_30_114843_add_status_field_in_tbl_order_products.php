<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldInTblOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('order_products', function (Blueprint $table) {
            $table->string('status',1)->default('A')->after('final_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
