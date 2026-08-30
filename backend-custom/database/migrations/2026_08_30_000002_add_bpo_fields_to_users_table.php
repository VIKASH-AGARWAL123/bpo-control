<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('organization_id')->nullable()->after('id')->constrained('organizations')->cascadeOnDelete();
            $table->string('role')->default('agent')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->index(['organization_id', 'role']);
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropIndex(['organization_id', 'role']);
            $table->dropColumn(['organization_id','role','is_active']);
        });
    }
};
