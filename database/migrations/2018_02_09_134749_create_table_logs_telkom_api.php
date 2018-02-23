<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLogsTelkomApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs_telkom_api', function (Blueprint $table) {
            $table->increments('id');
            $table->text('response')->comment('response by Telkom API');
            $table->integer('type')->comment("1: OTP, 2: Verifikasi OTP, 3: SMS Notif, 4. Helio Login, 5. Helio Compose Email");
            $table->text('param')->comment("Parameter send by user");
            $table->integer('status')->comment("1: sukses, 2:failed");
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
        Schema::dropIfExists('logs_telkom_api');
    }
}
