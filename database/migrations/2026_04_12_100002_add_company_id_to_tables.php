<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add company_id to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete()->after('id');
            $table->string('role')->default('company_admin')->after('company_id');
        });

        // Add company_id to all inventory tables
        foreach (['categories', 'suppliers', 'parts', 'transactions'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete()->after('id');
            });
        }
    }

    public function down(): void
    {
        foreach (['categories', 'suppliers', 'parts', 'transactions'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            });
        }
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'role']);
        });
    }
};
