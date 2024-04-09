<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artworks', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->integer('position')->unsigned()->nullable();
            $table->boolean('is_on_view')->nullable();
            $table->decimal('latitude', 15, 13)->nullable();
            $table->decimal('longitude', 15, 13)->nullable();

            $table->string('image_id')->nullable();

            $table->string('gallery_id')->nullable();
        });

        Schema::create('artwork_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'artwork');
            $table->string('title', 200)->nullable();
            $table->string('credit_line')->nullable();
            $table->string('copyright_notice')->nullable();
        });

        Schema::create('artwork_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'artwork');
        });
    }
};
