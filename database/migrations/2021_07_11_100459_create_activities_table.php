<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create(
            'activities',
            function (Blueprint $table) {
                $table->increments('id');
                $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
                $table->string('description');
                $table->timestamps();
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
