<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedInteger('passing_percentage');
            $table->string('title');
            $table->float('passing_percentage')->default(70.0);
            $table->integer('max_attempts')->default(3);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
