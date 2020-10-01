<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_examples')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_examples', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->references('id')
                ->on('enlighten_example_groups');

            $table->string('method_name');

            $table->unique(['group_id', 'method_name']);

            $table->string('title');
            $table->string('description')->nullable();
            $table->json('request_headers');
            $table->string('request_method');
            $table->string('request_path');
            $table->json('request_query_parameters');
            $table->json('request_input');
            $table->string('route');
            $table->json('route_parameters');
            $table->char('response_status', 3);
            $table->json('response_headers');
            $table->text('response_body');
            $table->text('response_template')->nullable();

            $table->text('session_data')->nullable();

            $table->string('test_status')->nullable();

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
        Schema::connection('enlighten')->dropIfExists('enlighten_examples');
    }
}
