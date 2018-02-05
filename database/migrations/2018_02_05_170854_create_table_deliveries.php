<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDeliveries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('track_id')->nullable();
            $table->integer('package_id');
            $table->decimal('current_lat', 20,8)->nullable()->default(0);
            $table->decimal('current_longi', 20,8)->nullable()->default(0);
            $table->decimal('destination_lat', 20,8)->nullable()->default(0);
            $table->decimal('destination_longi', 20,8)->nullable()->default(0);
            $table->integer('courrier_id')->nullable();
            $table->integer('status')->nullable()->comment('1:Pending, 2:In-Progress, 3:Done');
            $table->integer('finished_at')->nullable();
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
        Schema::dropIfExists('deliveries');
    }
}
