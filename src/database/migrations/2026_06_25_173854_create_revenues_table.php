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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->string('revenue_nom');
            $table->string('revenue_description');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->decimal('revenue_montant');
            $table->string('revenue_fractionnement');
            $table->date('revenue_date_effet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
