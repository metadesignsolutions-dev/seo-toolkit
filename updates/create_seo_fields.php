<?php namespace Metadesignsolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateSeoFields extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mds_seo_fields')) {
            Schema::create('mds_seo_fields', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('seo_title')->nullable();
                $table->text('seo_description')->nullable();
                $table->string('seo_keywords')->nullable();
                $table->string('seo_image')->nullable();
                $table->string('model_type');
                $table->integer('model_id');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mds_seo_fields');
    }
}