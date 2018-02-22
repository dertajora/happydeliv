<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiCredentials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_credentials', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->text('client_id');
            $table->text('client_secret');
            $table->text('token_api')->nullable();
            $table->timestamp('token_generated_time')->nullable();
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
        Schema::dropIfExists('api_credentials');
    }
}
