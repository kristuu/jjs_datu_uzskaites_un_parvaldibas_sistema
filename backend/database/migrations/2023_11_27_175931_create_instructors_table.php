<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->date('job_start_date');
            $table->string('user_person_code', 11);
            $table->foreign('user_person_code')->references('person_code')->on('users')->onUpdate('CASCADE');
            $table->unsignedBigInteger('certificate_id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
