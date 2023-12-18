<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Create the new foreign key column
            $table->unsignedBigInteger('instructor_availability_id');
            $table->foreign('instructor_availability_id')
                ->references('id')
                ->on('instructors_availabilities')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
