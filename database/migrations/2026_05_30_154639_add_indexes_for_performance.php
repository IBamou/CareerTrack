<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->index('status');
            $table->index('priority');
            $table->index(['applied_by', 'status']);
            $table->index(['applied_by', 'deleted_at']);
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->index('scheduled_at');
            $table->index(['user_id', 'scheduled_at']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index(['user_id', 'deleted_at']);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->index(['user_id', 'deleted_at']);
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['applied_by', 'status']);
            $table->dropIndex(['applied_by', 'deleted_at']);
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['user_id', 'scheduled_at']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'deleted_at']);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'deleted_at']);
        });
    }
};
