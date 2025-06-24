<?php namespace Metadesignsolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddSeoColumnsToRainlabPagesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('rainlab_pages')) {
            Schema::table('rainlab_pages', function($table)
            {
                if (!Schema::hasColumn($table->getTable(), 'seo_title')) {
                    $table->string('seo_title', 255)->nullable();
                }
                if (!Schema::hasColumn($table->getTable(), 'seo_description')) {
                    $table->text('seo_description')->nullable();
                }
                if (!Schema::hasColumn($table->getTable(), 'seo_keywords')) {
                    $table->string('seo_keywords', 255)->nullable();
                }
                if (!Schema::hasColumn($table->getTable(), 'seo_image')) {
                    $table->string('seo_image', 255)->nullable();
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('rainlab_pages')) {
            Schema::table('rainlab_pages', function($table)
            {
                if (Schema::hasColumn($table->getTable(), 'seo_title')) {
                    $table->dropColumn('seo_title');
                }
                if (Schema::hasColumn($table->getTable(), 'seo_description')) {
                    $table->dropColumn('seo_description');
                }
                if (Schema::hasColumn($table->getTable(), 'seo_keywords')) {
                    $table->dropColumn('seo_keywords');
                }
                if (Schema::hasColumn($table->getTable(), 'seo_image')) {
                    $table->dropColumn('seo_image');
                }
            });
        }
    }
} 