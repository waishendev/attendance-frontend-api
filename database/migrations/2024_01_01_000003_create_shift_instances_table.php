<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shift_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_instances');
    }
};
