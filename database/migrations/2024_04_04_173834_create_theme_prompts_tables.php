<?php

use App\Models\Theme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('theme_prompts', function (Blueprint $table) {
            createDefaultTableFields($table);

            $table->integer('position')->unsigned()->nullable();

            $table->foreignIdFor(Theme::class);
        });

        Schema::create('theme_prompt_translations', function (Blueprint $table) {
            createDefaultTranslationsTableFields($table, 'theme_prompt');
            $table->string('title', 200)->nullable();
            $table->text('subtitle')->nullable();
        });

        Schema::create('theme_prompt_revisions', function (Blueprint $table) {
            createDefaultRevisionsTableFields($table, 'theme_prompt');
        });
    }
};
