<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('action'); // granted, revoked, synced, template_assigned, synced_with_template
            $table->foreignId('permission_id')->nullable()->constrained();
            $table->json('permissions_snapshot')->nullable(); // for bulk operations
            $table->foreignId('performed_by')->constrained('users');
            $table->text('reason')->nullable();
            $table->timestamp('performed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_audit_logs');
    }
};
