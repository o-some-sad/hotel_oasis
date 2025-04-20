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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unique();
            $table->integer('capacity');
            $table->bigInteger('price');
            $table->unsignedBigInteger('floor_id');
            $table->unsignedBigInteger('created_by');
            $table->foreign('floor_id')->references('id')->on('floors');
            $table->foreign('created_by')->references('id')->on('users');
            $table->boolean('reserved')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
