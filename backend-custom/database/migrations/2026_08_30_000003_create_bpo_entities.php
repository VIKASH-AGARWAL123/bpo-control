<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('clients', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->string('code')->nullable(); $t->string('email')->nullable(); $t->string('status')->default('active'); $t->timestamps();
            $t->index(['organization_id','status']);
        });
        Schema::create('processes', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $t->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $t->string('name'); $t->string('code')->nullable(); $t->text('description')->nullable(); $t->string('status')->default('active'); $t->timestamps();
            $t->index(['organization_id','client_id']);
        });
        Schema::create('teams', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->text('description')->nullable(); $t->string('status')->default('active'); $t->timestamps(); $t->index('organization_id');
        });
        Schema::create('queues', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $t->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $t->string('name'); $t->string('status')->default('active'); $t->timestamps(); $t->index(['organization_id','team_id']);
        });
        Schema::create('tasks', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete();
            foreach(['client_id','process_id','team_id','queue_id'] as $fk) $t->foreignId($fk)->nullable()->constrained($fk === 'client_id' ? 'clients' : ($fk === 'process_id' ? 'processes' : ($fk === 'team_id' ? 'teams' : 'queues')))->nullOnDelete();
            $t->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('task_number')->unique(); $t->string('title'); $t->text('description')->nullable();
            $t->string('status')->default('pending'); $t->string('priority')->default('medium'); $t->string('task_type')->nullable();
            $t->string('category')->nullable(); $t->string('subcategory')->nullable(); $t->string('source')->nullable(); $t->string('external_reference_id')->nullable();
            $t->timestamp('started_at')->nullable(); $t->timestamp('due_at')->nullable(); $t->timestamp('completed_at')->nullable();
            $t->string('sla_status')->default('on_track'); $t->timestamp('sla_deadline_at')->nullable(); $t->integer('sla_consumed_seconds')->default(0); $t->integer('sla_remaining_seconds')->nullable(); $t->integer('escalation_level')->default(0);
            $t->json('custom_fields')->nullable(); $t->timestamps();
            $t->index(['organization_id','status']); $t->index(['organization_id','assignee_id','status']); $t->index(['organization_id','client_id','status']); $t->index(['organization_id','process_id','status']); $t->index(['organization_id','queue_id','status']); $t->index(['organization_id','sla_status']); $t->index(['organization_id','due_at']);
        });
        Schema::create('automations', function(Blueprint $t){
            $t->id(); $t->foreignId('organization_id')->constrained()->cascadeOnDelete(); $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->string('name'); $t->string('trigger'); $t->json('conditions')->nullable(); $t->json('actions'); $t->boolean('enabled')->default(true); $t->timestamps(); $t->index(['organization_id','enabled']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('automations'); Schema::dropIfExists('tasks'); Schema::dropIfExists('queues'); Schema::dropIfExists('teams'); Schema::dropIfExists('processes'); Schema::dropIfExists('clients');
    }
};
