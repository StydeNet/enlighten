<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlightenExampleGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('enlighten')->hasTable('enlighten_example_groups')) {
            return;
        }

        Schema::connection('enlighten')->create('enlighten_example_groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('run_id')
                ->references('id')
                ->on('enlighten_runs')
                ->cascadeOnDelete();

            $table->string('class_name');

            $table->unique(['run_id', 'class_name']);

            $table->string('title');
            $table->string('slug');

            $table->string('description')->nullable();
            $table->string('area');

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
        Schema::connection('enlighten')->dropIfExists('enlighten_example_groups');
    }
}
