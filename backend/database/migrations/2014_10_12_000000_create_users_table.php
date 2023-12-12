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
        Schema::create('users', function (Blueprint $table) {
            $table->string('person_code', 11)->primary();
            $table->string('name', 60);
            $table->string('surname', 60);
            $table->date('birthdate');
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('phone', 32)->nullable();
            $table->string('googleplaces_address_code', 255)->nullable();
            $table->string('iban_code', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
