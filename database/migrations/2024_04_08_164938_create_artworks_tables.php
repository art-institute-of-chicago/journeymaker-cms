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
            $table->string('datahub_id')->unique();
            $table->boolean('is_on_view')->nullable();
            $table->string('image_id')->nullable();
        });

        Schema::create('artwork_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'artwork');
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->string('location_directions')->nullable();
        });

        Schema::create('artwork_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'artwork');
        });
    }
};
