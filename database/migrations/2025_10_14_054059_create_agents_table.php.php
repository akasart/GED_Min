<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 50)->unique();
            $table->string('nom', 100);
            $table->string('prenom', 100)->nullable();
            $table->string('direction', 100)->nullable();
            $table->string('service', 100)->nullable();
            $table->string('fonction', 100)->nullable();
            $table->string('ministere_rattachement', 100)->nullable();
            $table->date('date_entree')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('agents');
    }
};
