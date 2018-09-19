<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSnapshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('snapshots', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('status')->default('in_progress');
            $table->string('name');
            $table->string('url');
            $table->smallinteger('from')->unsigned()->nullable();
            $table->smallinteger('to')->unsigned()->nullable();
            $table->smallinteger('current')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('snapshots');
    }
}
