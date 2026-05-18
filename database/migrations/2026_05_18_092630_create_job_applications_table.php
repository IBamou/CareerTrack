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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('location_type');
            $table->string('location_city')->default(null);
            $table->json('links');
            $table->string('status')->default('applied');
            $table->timestamp('applied_at')->nullable();
            $table->dateTime('next_follow_up_at')->nullable();
            $table->text('notes');
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('applied_by')->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
