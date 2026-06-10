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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'staff'])->default('staff')->after('email');
            $table->string('google_id')->nullable()->after('remember_token');
            $table->string('avatar')->nullable()->after('google_id');
            $table->boolean('is_active')->default(true)->after('avatar');
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete()->after('is_active');
            $table->string('invitation_token')->nullable()->unique()->after('invited_by');
            $table->timestamp('invitation_accepted_at')->nullable()->after('invitation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'google_id', 'avatar', 'is_active', 'invited_by', 'invitation_token', 'invitation_accepted_at']);
        });
    }
};
