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
        Schema::create('revenus', function (Blueprint $table) {
            $table->id();
            $table->string('revenu_nom');
            $table->string('revenu_description');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->decimal('revenu_montant', 15, 2);
            $table->enum('revenu_fractionnement', ['mensuel', 'semestriel', 'annuel', 'unique']);
            $table->date('revenu_date_effet');
            $table->date('last_credited_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenus');
    }
};