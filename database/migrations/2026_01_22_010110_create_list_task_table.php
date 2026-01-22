<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('list_task', function (Blueprint $table) {
            $table->id();

            $table->foreignId('task_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('task_list_id')
                  ->constrained('task_lists')
                  ->cascadeOnDelete();

            $table->unique(['task_id', 'task_list_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_task');
    }
};
