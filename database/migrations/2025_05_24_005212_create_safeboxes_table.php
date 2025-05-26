<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('safeboxes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Added name field with unique constraint
            $table->enum('karat', ['18', '21', '24']);
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
            
            // Optional: Add index for better performance on frequently queried fields
            $table->index('karat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('safeboxes');
    }
};