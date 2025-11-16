<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure all existing agents have a user_id
        // Create users for any agents that don't have a user_id
        $agentsWithoutUser = DB::table('agents')->whereNull('user_id')->get();
        
        foreach ($agentsWithoutUser as $agent) {
            // Create a user for this agent
            $username = strtolower($agent->nom . ($agent->prenom ? '.' . $agent->prenom : ''));
            $username = preg_replace('/[^a-z0-9.]/', '', $username);
            
            // Ensure username is unique
            $baseUsername = $username;
            $counter = 1;
            while (DB::table('users')->where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $userId = DB::table('users')->insertGetId([
                'username' => $username,
                'email' => null,
                'password' => Hash::make('password'), // Default password, should be changed
                'role' => 'utilisateur',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Update agent with user_id
            DB::table('agents')->where('id', $agent->id)->update(['user_id' => $userId]);
        }
        
        Schema::table('agents', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['user_id']);
        });
        
        // Use raw SQL to modify the column since change() requires doctrine/dbal
        DB::statement('ALTER TABLE agents MODIFY user_id BIGINT UNSIGNED NOT NULL');
        
        Schema::table('agents', function (Blueprint $table) {
            // Re-add the foreign key constraint with cascade delete
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
        });
        
        // Use raw SQL to make user_id nullable again
        DB::statement('ALTER TABLE agents MODIFY user_id BIGINT UNSIGNED NULL');
        
        Schema::table('agents', function (Blueprint $table) {
            // Re-add the foreign key constraint with nullOnDelete
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
