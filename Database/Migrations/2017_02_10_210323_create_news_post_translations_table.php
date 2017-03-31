<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsPostTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news__post_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');

            $table->integer('post_id')->unsigned();
            $table->string('locale')->index();

            $table->string('title');
            $table->string('slug');
            $table->text('intro');
            $table->text('content');

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_type')->nullable();

            $table->unique(['post_id', 'locale']);
            $table->foreign('post_id')->references('id')->on('news__posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news__post_translations');
    }
}
