<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('montant', 15, 2);
            $table->enum('type', ['revenu', 'depense']);
            $table->enum('fractionnement', ['unique', 'mensuel', 'semestriel', 'annuel']);
            $table->date('date_effet');
            $table->date('date_fin')->nullable();
            $table->date('derniere_application')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
