<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users_permission_levels', function (Blueprint $table) {
            $table->id();
            $table->string('user_person_code', 11);
            $table->foreign('user_person_code')->references('person_code')->on('users')->onUpdate('CASCADE');
            $table->unsignedBigInteger('permission_level_id');
            $table->foreign('permission_level_id')->references('id')->on('permission_levels');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_permission_levels');
    }
};
