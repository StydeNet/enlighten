<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExampleQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_example_queries')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_example_queries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('example_id')
                ->references('id')
                ->on('enlighten_examples')
                ->cascadeOnDelete();

            $table->text('sql');

            $table->longText('bindings');

            $table->string('time');

            $table->foreignId('http_data_id')
                ->nullable()
                ->references('id')
                ->on('enlighten_http_data');

            $table->foreignId('snippet_call_id')
                ->nullable()
                ->references('id')
                ->on('enlighten_example_snippet_calls');

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
        Schema::connection('enlighten')->dropIfExists('enlighten_example_queries');
    }
}
