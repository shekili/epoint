<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('epoint_logs', function (Blueprint $table) {
            $table->id();
            $table->string('transaction')->nullable();
            $table->string('status');
            $table->string('description')->nullable();
            $table->string('code')->nullable();
            $table->string('message')->nullable();
            $table->string('order_id')->nullable();
            $table->string('bank_transaction')->nullable();
            $table->string('bank_response')->nullable();
            $table->string('card_name')->nullable();
            $table->string('card_mask')->nullable();
            $table->string('rrn')->nullable();
            $table->string('other_attr')->nullable();
            $table->string('trace_id')->nullable();
            $table->float('amount', 10, 2);
            $table->boolean('reviewed')->default(false);


            // Metadata
            $table->string('model_type')->nullable();  // Polimorfik əlaqə üçün
            $table->unsignedBigInteger('model_id')->nullable();
            $table->index(['model_type', 'model_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('epoint_logs');
    }
};
