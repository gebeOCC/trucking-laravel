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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('users');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_type');
            $table->string('goods_photo');
            $table->double('price', 10, 2);
            $table->string('pickup_type');
            $table->date('pick_up_date_time');

            $table->string('pickup_location');
            $table->string('pickup_location_address');
            $table->string('sender_name');
            $table->string('sender_contact_number');
            $table->string('pickup_location_details');
            
            $table->string('dropoff_location');
            $table->string('dropoff_location_address');
            $table->string('recipient_name');
            $table->string('recipient_contact_number');
            $table->string('dropoff_location_details');


            $table->double('distance', 10, 2);
            $table->double('duration', 10, 2);

            $table->enum('booking_status', ['pending','approved']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
