<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCssVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('css_variables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('css_variable_category_id')->constrained()->onDelete('cascade');
            $table->string('variable_name', 50);
            $table->string('variable_value', 50);
            $table->string('variable_default_value', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('css_variables');
    }
}
