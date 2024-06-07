<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('user_person_code');
            $table->string('short_message');
            $table->longText('long_message');
            $table->timestamps();

            $table->foreign('user_person_code')->references('person_code')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
