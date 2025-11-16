<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->text('motif_rejet')->nullable()->after('observation');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete()->after('created_by');
            $table->timestamp('validated_at')->nullable()->after('validated_by');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete()->after('validated_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn(['motif_rejet', 'validated_by', 'validated_at', 'rejected_by', 'rejected_at']);
        });
    }
};
