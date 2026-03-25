<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->json('options');
            $table->unsignedInteger('correct_option_index');
            $table->string('question_type')->default('multiple_choice');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
