<?php namespace Metadesignsolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateDefinitionsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('metadesignsolutions_mdsoctoberseo_definitions')) {
            Schema::create('metadesignsolutions_mdsoctoberseo_definitions', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('theme')->nullable();
                $table->string('type')->nullable();
                $table->string('url')->nullable();
                $table->string('reference')->nullable();
                $table->string('cmsPage')->nullable();
                $table->string('changefreq')->nullable();
                $table->string('priority')->nullable();
                $table->boolean('nesting')->default(false);
                $table->boolean('allow_nesting')->default(false);
                $table->text('data')->nullable(); // Keep this if you still need to store some JSON data, otherwise remove it.
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('metadesignsolutions_mdsoctoberseo_definitions');
    }
}