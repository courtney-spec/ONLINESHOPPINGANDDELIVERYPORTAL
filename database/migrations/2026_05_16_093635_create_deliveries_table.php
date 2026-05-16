<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'dispatched',
                'out_for_delivery',
                'delivered',
                'failed'
            ])->default('pending');
            $table->string('delivery_address');
            $table->string('recipient_name');
            $table->string('recipient_phone')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('tracking_number')->nullable()->unique();
            $table->date('estimated_delivery_date')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
