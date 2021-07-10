<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create(
            'tasks',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->text('body');
                $table->boolean('completed')->default(false);
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
