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
                    // Basic SEO fields
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
                    
                    // Open Graph fields
                    if (!Schema::hasColumn($tableName, 'og_title')) {
                        $table->string('og_title')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'og_description')) {
                        $table->text('og_description')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'og_image')) {
                        $table->string('og_image')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'og_type')) {
                        $table->string('og_type')->nullable();
                    }
                    
                    // Twitter/X fields
                    if (!Schema::hasColumn($tableName, 'twitter_title')) {
                        $table->string('twitter_title')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'twitter_description')) {
                        $table->text('twitter_description')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'twitter_card')) {
                        $table->string('twitter_card')->nullable();
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
                        'seo_image',
                        'og_title',
                        'og_description',
                        'og_image',
                        'og_type',
                        'twitter_title',
                        'twitter_description',
                        'twitter_card'
                    ]);
                });
            }
        }
    }
}