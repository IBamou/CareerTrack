<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->decimal('salary_min', 12, 2)->nullable()->after('priority');
            $table->decimal('salary_max', 12, 2)->nullable()->after('salary_min');
            $table->string('currency', 3)->default('USD')->after('salary_max');
            $table->text('benefits')->nullable()->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['salary_min', 'salary_max', 'currency', 'benefits']);
        });
    }
};
