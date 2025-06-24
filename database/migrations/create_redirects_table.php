<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectsTable extends Migration
{
    public function up()
    {
        Schema::create('mdsoctoberseo_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url');
            $table->string('to_url');
            $table->integer('status_code');
            $table->boolean('is_active')->default(true); // Ensure this column is present
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mdsoctoberseo_redirects');
    }
}