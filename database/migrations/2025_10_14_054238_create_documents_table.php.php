<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 150);
            $table->string('fichier', 255);
            $table->dateTime('date_creation')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->enum('etat', ['En attente', 'Validé', 'Rejeté', 'Archivé'])->default('En attente');
            $table->text('observation')->nullable();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('documents');
    }
};
