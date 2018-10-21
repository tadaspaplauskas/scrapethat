<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatevariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('snapshot_id')->unsigned()->index();
            $table->string('name');
            $table->string('selector');
            $table->smallinteger('current_page')->unsigned()->default(0);
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
        Schema::dropIfExists('variables');
    }
}
