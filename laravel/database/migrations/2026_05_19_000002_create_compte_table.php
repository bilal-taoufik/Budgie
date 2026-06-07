<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compte', function (Blueprint $table) {
            $table->id('cmp_id');
            $table->string('cmp_nom_appel', 100);
            $table->string('cmp_description')->nullable();
            $table->date('cmp_date_creation');
            $table->decimal('cmp_solde_initial', 15, 2)->default(0);
            $table->decimal('cmp_taux_remuneration', 5, 2)->nullable();
            $table->decimal('cmp_taux_imposition', 5, 2)->nullable();
            $table->foreignId('cmp_client_id')
                ->constrained('client')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compte');
    }
};
