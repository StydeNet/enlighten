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
                ->on('enlighten_example_groups')
                ->cascadeOnDelete();

            $table->string('method_name');
            $table->string('data_name')->nullable();
            $table->json('provided_data')->nullable();

            $table->string('slug');

            $table->unique(['group_id', 'method_name', 'data_name']);
            $table->unique(['group_id', 'slug', 'data_name']);

            $table->integer('line')->nullable();
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('test_status')->nullable();
            $table->string('status')->nullable();

            $table->unsignedInteger('order_num')->nullable();

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
