<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Normalize invalid role values to 'utilisateur'
        DB::statement("UPDATE users SET role = 'utilisateur' WHERE role NOT IN ('admin', 'rh', 'utilisateur')");

        // Modify the ENUM to use 'admin', 'rh', 'utilisateur'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'rh', 'utilisateur') DEFAULT 'utilisateur'");
    }

    public function down()
    {
        // No action needed on rollback - keep the ENUM as is
        // This prevents errors when rolling back
    }
};


