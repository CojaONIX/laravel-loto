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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->year('year');
            $table->unsignedTinyInteger('round');
            $table->unsignedTinyInteger('n1');
            $table->unsignedTinyInteger('n2');
            $table->unsignedTinyInteger('n3');
            $table->unsignedTinyInteger('n4');
            $table->unsignedTinyInteger('n5');
            $table->unsignedFloat('winning', 8, 2);
            $table->boolean('paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
