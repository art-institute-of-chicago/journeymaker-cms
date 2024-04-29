<?php

use App\Models\Artwork;
use App\Models\ThemePrompt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('theme_prompt_artworks', function (Blueprint $table) {
            createDefaultTableFields($table, true, false);
            $table->string('title')->nullable();
            $table->integer('position')->unsigned()->nullable();
            $table->integer('activity_template')->nullable();

            $table->foreignIdFor(ThemePrompt::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignIdFor(Artwork::class)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });

        Schema::create('theme_prompt_artwork_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'theme_prompt_artwork');
            $table->string('detail_narrative')->nullable();
            $table->string('viewing_description')->nullable();
            $table->string('activity_instructions')->nullable();
        });

        Schema::create('theme_prompt_artwork_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'theme_prompt_artwork');
        });
    }
};
