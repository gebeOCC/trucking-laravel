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
        Schema::create('departure', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_id');
            $table->foreign('travel_id')->references('id')->on('travels');
            $table->date('departure_date_time');
            $table->string('goods_photo_pickup');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departure');
    }
};
