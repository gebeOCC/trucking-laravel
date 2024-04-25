<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('arrival', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_id');
            $table->foreign('travel_id')->references('id')->on('travels');
            $table->date('departure_date_time');
            $table->string('goods_photo_drop_off');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('signature_image');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrival');
    }
};
