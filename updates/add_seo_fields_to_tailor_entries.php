<?php namespace MetadesignSolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Tailor\Classes\Blueprint\EntryBlueprint;
use System\Classes\PluginManager;

class AddSeoFieldsToTailorEntries extends Migration
{
    public function up()
    {
        // Check if Tailor plugin is installed
        if (!PluginManager::instance()->hasPlugin('Tailor')) {
            return;
        }

        // Get all entry blueprints
        $blueprints = EntryBlueprint::listInProject();

        foreach ($blueprints as $blueprint) {
            $tableName = 'tailor_content_' . $blueprint->uuid;
            
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function($table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'seo_title')) {
                        $table->string('seo_title')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'seo_description')) {
                        $table->text('seo_description')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'seo_keywords')) {
                        $table->string('seo_keywords')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'seo_image')) {
                        $table->string('seo_image')->nullable();
                    }
                });
            }
        }
    }

    public function down()
    {
        if (!PluginManager::instance()->hasPlugin('Tailor')) {
            return;
        }

        $blueprints = EntryBlueprint::listInProject();

        foreach ($blueprints as $blueprint) {
            $tableName = 'tailor_content_' . $blueprint->uuid;
            
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function($table) {
                    $table->dropColumn([
                        'seo_title',
                        'seo_description',
                        'seo_keywords',
                        'seo_image'
                    ]);
                });
            }
        }
    }
}