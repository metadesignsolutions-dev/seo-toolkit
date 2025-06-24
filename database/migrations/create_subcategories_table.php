<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mds_productmanagement_subcategories')) {
            Schema::create('mds_productmanagement_subcategories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug');
                $table->unsignedInteger('category_id');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mds_productmanagement_subcategories');
    }
}