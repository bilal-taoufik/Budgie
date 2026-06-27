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
            $table->enum('revenue_fractionnement', ['mensuel', 'semestriel', 'annuel', 'une_fois']);
            $table->date('revenue_date_effet');
            $table->date('last_credited_at')->nullable();
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
