<?php namespace Metadesignsolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use DB;

class AddSeoColumnsToContent extends Migration
{
    public function up()
    {
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if (strpos($tableName, 'xc_') === 0) {
                $columnsToAdd = [];
                if (!Schema::hasColumn($tableName, 'seo_title')) {
                    $columnsToAdd[] = 'seo_title';
                }
                if (!Schema::hasColumn($tableName, 'seo_description')) {
                    $columnsToAdd[] = 'seo_description';
                }
                if (!Schema::hasColumn($tableName, 'seo_keywords')) {
                    $columnsToAdd[] = 'seo_keywords';
                }
               
                // OG fields
                if (!Schema::hasColumn($tableName, 'og_title')) {
                    $columnsToAdd[] = 'og_title';
                }
                if (!Schema::hasColumn($tableName, 'og_description')) {
                    $columnsToAdd[] = 'og_description';
                }
                if (!Schema::hasColumn($tableName, 'og_image')) {
                    $columnsToAdd[] = 'og_image';
                }
                if (!Schema::hasColumn($tableName, 'og_type')) {
                    $columnsToAdd[] = 'og_type';
                }
                // Twitter fields
                if (!Schema::hasColumn($tableName, 'twitter_title')) {
                    $columnsToAdd[] = 'twitter_title';
                }
                if (!Schema::hasColumn($tableName, 'twitter_description')) {
                    $columnsToAdd[] = 'twitter_description';
                }
                if (!Schema::hasColumn($tableName, 'twitter_card')) {
                    $columnsToAdd[] = 'twitter_card';
                }
                // Schema.org field
                if (!Schema::hasColumn($tableName, 'schema_jsonld')) {
                    $columnsToAdd[] = 'schema_jsonld';
                }
                if (!empty($columnsToAdd)) {
                    Schema::table($tableName, function($table) use ($columnsToAdd) {
                        if (in_array('seo_title', $columnsToAdd)) {
                            $table->string('seo_title', 255)->nullable();
                        }
                        if (in_array('seo_description', $columnsToAdd)) {
                            $table->text('seo_description')->nullable();
                        }
                        if (in_array('seo_keywords', $columnsToAdd)) {
                            $table->string('seo_keywords', 255)->nullable();
                        }
                       
                        // OG fields
                        if (in_array('og_title', $columnsToAdd)) {
                            $table->string('og_title')->nullable();
                        }
                        if (in_array('og_description', $columnsToAdd)) {
                            $table->text('og_description')->nullable();
                        }
                        if (in_array('og_image', $columnsToAdd)) {
                            $table->string('og_image')->nullable();
                        }
                        if (in_array('og_type', $columnsToAdd)) {
                            $table->string('og_type')->default('website');
                        }
                        // Twitter fields
                        if (in_array('twitter_title', $columnsToAdd)) {
                            $table->string('twitter_title')->nullable();
                        }
                        if (in_array('twitter_description', $columnsToAdd)) {
                            $table->text('twitter_description')->nullable();
                        }
                        if (in_array('twitter_card', $columnsToAdd)) {
                            $table->string('twitter_card')->default('summary_large_image');
                        }
                        // Schema.org field
                        if (in_array('schema_jsonld', $columnsToAdd)) {
                            $table->text('schema_jsonld')->nullable();
                        }
                    });
                }
            }
        }
    }

    public function down()
    {
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if (strpos($tableName, 'xc_') === 0) {
                $columnsToDrop = [];
                if (Schema::hasColumn($tableName, 'seo_title')) {
                    $columnsToDrop[] = 'seo_title';
                }
                if (Schema::hasColumn($tableName, 'seo_description')) {
                    $columnsToDrop[] = 'seo_description';
                }
                if (Schema::hasColumn($tableName, 'seo_keywords')) {
                    $columnsToDrop[] = 'seo_keywords';
                }
                
                // OG fields
                if (Schema::hasColumn($tableName, 'og_title')) {
                    $columnsToDrop[] = 'og_title';
                }
                if (Schema::hasColumn($tableName, 'og_description')) {
                    $columnsToDrop[] = 'og_description';
                }
                if (Schema::hasColumn($tableName, 'og_image')) {
                    $columnsToDrop[] = 'og_image';
                }
                if (Schema::hasColumn($tableName, 'og_type')) {
                    $columnsToDrop[] = 'og_type';
                }
                // Twitter fields
                if (Schema::hasColumn($tableName, 'twitter_title')) {
                    $columnsToDrop[] = 'twitter_title';
                }
                if (Schema::hasColumn($tableName, 'twitter_description')) {
                    $columnsToDrop[] = 'twitter_description';
                }
                if (Schema::hasColumn($tableName, 'twitter_card')) {
                    $columnsToDrop[] = 'twitter_card';
                }
                // Schema.org field
                if (Schema::hasColumn($tableName, 'schema_jsonld')) {
                    $columnsToDrop[] = 'schema_jsonld';
                }
                if (!empty($columnsToDrop)) {
                    Schema::table($tableName, function($table) use ($columnsToDrop) {
                        $table->dropColumn($columnsToDrop);
                    });
                }
            }
        }
    }
}