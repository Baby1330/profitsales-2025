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
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Sales::class)->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->tinyInteger('month'); 
            $table->integer('targetprod')->default(5); 
            $table->timestamps();

            $table->unique(['sales_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
