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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
        
        Schema::create('assigntasks', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->integer('userid');
            $table->integer('taskid');
            $table->integer('taskstatus')->default(0);
            $table->integer('created_by');
            $table->timestamp('taskstatus_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('assigntasks');
    }
};
