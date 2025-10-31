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
        Schema::table('registrations', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('phone');
            $table->boolean('created_by_admin')->default(false)->after('notes');
            $table->foreignId('admin_user_id')->nullable()->constrained('users')->after('created_by_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['admin_user_id']);
            $table->dropColumn(['notes', 'created_by_admin', 'admin_user_id']);
        });
    }
};
