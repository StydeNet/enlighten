<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExampleSnippetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_example_snippets')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_example_snippets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('example_id')
                ->references('id')
                ->on('enlighten_examples')
                ->cascadeOnDelete();

            $table->longText('code');

            $table->longText('result')->nullable();

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
        Schema::connection('enlighten')->dropIfExists('enlighten_example_snippets');
    }
}
