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
            $table->string('qr_code_token')->unique()->nullable()->after('stripe_session_id');
            $table->boolean('checked_in')->default(false)->after('qr_code_token');
            $table->timestamp('checked_in_at')->nullable()->after('checked_in');
            $table->unsignedBigInteger('checked_in_by')->nullable()->after('checked_in_at');
            
            $table->foreign('checked_in_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['checked_in_by']);
            $table->dropColumn(['qr_code_token', 'checked_in', 'checked_in_at', 'checked_in_by']);
        });
    }
};
