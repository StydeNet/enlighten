<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_exceptions')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_exceptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('example_id')
                ->unique()
                ->references('id')
                ->on('enlighten_examples');

            $table->string('code');
            $table->string('message');
            $table->string('file');
            $table->unsignedSmallInteger('line');
            $table->longText('trace');

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
        Schema::connection('enlighten')->dropIfExists('enlighten_exceptions');
    }
}
