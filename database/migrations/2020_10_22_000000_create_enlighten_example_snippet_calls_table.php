<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExampleSnippetCallsTable extends Migration
{
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_example_snippet_calls')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_example_snippet_calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('example_snippet_id')
                ->references('id')
                ->on('enlighten_example_snippets')
                ->cascadeOnDelete();

            $table->longText('arguments');
            $table->longText('result')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('enlighten')->dropIfExists('enlighten_example_snippet_calls');
    }
}
