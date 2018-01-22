<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('snapshot_id')->unsigned()->index();
            $table->string('name');
            $table->string('selector');
            $table->smallinteger('scanned')->unsigned()->default(0);
            $table->json('values')->nullable();
            $table->timestamps();

            $table->unique(['snapshot_id', 'name']);
            $table->unique(['snapshot_id', 'selector']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}
