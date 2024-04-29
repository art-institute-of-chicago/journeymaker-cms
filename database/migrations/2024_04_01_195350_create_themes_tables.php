<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->integer('position')->unsigned()->nullable();
        });

        Schema::create('theme_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'theme');
            $table->string('title', 200)->nullable();
            $table->text('intro')->nullable();
            $table->text('journey_guide')->nullable();
        });

        Schema::create('theme_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'theme');
        });
    }
};
