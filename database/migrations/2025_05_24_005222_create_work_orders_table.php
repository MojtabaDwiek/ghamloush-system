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
    Schema::create('work_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained();
        $table->foreignId('safebox_id')->constrained();
        $table->string('type');
        $table->decimal('start_amount', 10, 2);
        $table->decimal('finish_amount', 10, 2)->nullable();
        $table->date('start_date');
        $table->date('end_date')->nullable();
        $table->decimal('loss', 10, 2)->nullable();
        $table->enum('status', ['pending', 'completed'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
