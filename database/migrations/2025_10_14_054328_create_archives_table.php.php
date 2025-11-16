<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->dateTime('date_archive')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('raison')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('archives');
    }
};
