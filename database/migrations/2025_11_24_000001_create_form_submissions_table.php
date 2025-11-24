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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_block_id')->constrained('homepage_content_blocks')->onDelete('cascade');
            $table->string('form_title');
            $table->string('csv_filename')->nullable();
            $table->integer('submission_count')->default(0);
            $table->timestamps();
            
            $table->index('content_block_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
