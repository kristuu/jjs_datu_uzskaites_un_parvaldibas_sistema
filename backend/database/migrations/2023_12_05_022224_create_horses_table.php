<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('horses', function (Blueprint $table) {
            $table->string('passport_number', 32)->primary();
            $table->string('name');
            $table->date('birthdate');
            $table->unsignedBigInteger('breed_id');
            $table->foreign('breed_id')->references('id')->on('breeds')->onUpdate('CASCADE');
            $table->string('owner_person_code', 11);
            $table->foreign('owner_person_code')->references('person_code')->on('users')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horses');
    }
};
