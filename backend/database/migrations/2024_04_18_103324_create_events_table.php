<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->unsignedBigInteger('event_category_id');
            $table->unsignedBigInteger('event_type_id');
            $table->unsignedBigInteger('location_id');
            $table->longText('comment')->nullable();
            $table->timestamps();
            $table->foreign('event_category_id')->references('id')->on('event_categories')->onUpdate('cascade');
            $table->foreign('event_type_id')->references('id')->on('event_types')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
