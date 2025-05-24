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
    Schema::create('work_order_changes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('work_order_id')->constrained();
        $table->foreignId('safebox_id')->nullable()->constrained();
        $table->decimal('weight', 10, 2)->nullable();
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_changes');
    }
};
