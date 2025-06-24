<?php namespace Metadesignsolutions\Mdsoctoberseo\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRedirectsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('mdsoctoberseo_redirects')) {
            Schema::create('mdsoctoberseo_redirects', function($table) {
                $table->increments('id');
                $table->string('from_url');
                $table->string('to_url');
                $table->integer('status_code')->default(301);
                $table->boolean('is_active')->default(true); // Added is_active column
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('mdsoctoberseo_redirects');
    }
}