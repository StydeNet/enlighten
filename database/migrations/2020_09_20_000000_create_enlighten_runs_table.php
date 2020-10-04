<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenRunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_runs')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_runs', function (Blueprint $table) {
            $table->id();

            $table->string('branch');
            $table->string('head');
            $table->boolean('modified');

            $table->unique(['head', 'modified']);

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
        Schema::connection('enlighten')->dropIfExists('enlighten_runs');
    }
}
