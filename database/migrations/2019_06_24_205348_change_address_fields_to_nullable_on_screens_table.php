<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAddressFieldsToNullableOnScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('screens', function (Blueprint $table) {
          $table->string('address')->nullable()->change();
          $table->string('city')->nullable()->change();
          $table->string('state')->nullable()->change();
          $table->string('zip')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('screens', function (Blueprint $table) {
          $table->string('address')->change();
          $table->string('city')->change();
          $table->string('state')->change();
          $table->string('zip')->change();
        });
    }
}
