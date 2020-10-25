<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExampleRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_example_requests')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_example_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('example_id')
                ->references('id')
                ->on('enlighten_examples')
                ->cascadeOnDelete();

            $table->json('request_headers');
            $table->string('request_method');
            $table->string('request_path');
            $table->json('request_query_parameters');
            $table->json('request_input');
            $table->string('route')->nullable();
            $table->json('route_parameters')->nullable();
            $table->char('response_status', 3)->nullable();
            $table->json('response_headers')->nullable();
            $table->longText('response_body')->nullable();
            $table->text('response_template')->nullable();

            $table->text('session_data')->nullable();

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
        Schema::connection('enlighten')->dropIfExists('enlighten_example_requests');
    }
}
