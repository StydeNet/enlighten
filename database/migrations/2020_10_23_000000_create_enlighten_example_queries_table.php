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

            $table->foreignId('request_id')
                ->nullable()
                ->references('id')
                ->on('enlighten_example_requests');

            $table->foreignId('snippet_id')
                ->nullable()
                ->references('id')
                ->on('enlighten_example_snippets');

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
