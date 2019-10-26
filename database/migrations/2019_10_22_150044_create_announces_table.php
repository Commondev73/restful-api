<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announces', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('announcer_status');
            $table->string('announcement_type');
            $table->string('Property_type');
            $table->integer('province_code');
            $table->integer('amphoe_code');
            $table->integer('district_code');
            $table->string('topic');
            $table->string('detail');
            $table->integer('bedroom');
            $table->integer('toilet');
            $table->integer('floor');
            $table->decimal('area');
            $table->bigInteger('price');
            $table->unsignedBigInteger('id_user');
            $table->integer('status')->default('0');
            $table->timestamps();

            $table->foreign('id_user')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announces');
    }
}
