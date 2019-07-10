<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLatAndLngToScreensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('screens', function (Blueprint $table) {
          $table->float('lat', 9, 6)
            ->nullable()
            ->after('zip');

            $table->float('lng', 9, 6)
              ->nullable()
              ->after('lat');
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
          $table->dropColumn('lat');
          $table->dropColumn('lng');
        });
    }
}
