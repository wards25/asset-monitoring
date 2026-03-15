<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('sticker_no', 30)->unique();   // e.g. SD-0001-M
            $table->string('qr_data', 500)->nullable();   // JSON string for QR code
            $table->enum('type', ['system_unit', 'monitor', 'mouse', 'keyboard', 'avr', 'laptop', 'printer']);
            $table->string('brand', 100);
            $table->string('model', 100)->nullable();
            $table->string('serial_no', 100)->nullable();
            $table->enum('status', ['new', 'working', 'defective', 'for_disposal', 'disposed'])->default('new');
            $table->string('department', 100)->nullable();
            $table->string('assigned_to', 150)->nullable();
            $table->string('old_user', 150)->nullable();
            $table->date('date_purchased')->nullable();
            $table->date('date_deployed')->nullable();
            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->string('supplier', 150)->nullable();
            $table->text('specs')->nullable();
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('department');
            $table->index('sticker_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};