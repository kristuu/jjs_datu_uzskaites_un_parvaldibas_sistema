<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->text('description');
            $table->enum('rating', ['1', '2', '3', '4', '5']);
        });
    }

    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            //
        });
    }
};
