<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLogoToHostnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hostnames', function (Blueprint $table) {
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('mini_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hostnames', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('logo');
            $table->dropColumn('mini_logo');
        });
    }
}
