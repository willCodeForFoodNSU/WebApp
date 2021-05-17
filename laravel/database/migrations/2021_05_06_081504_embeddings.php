<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Embeddings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embeddings', function (Blueprint $table) {
            $table->string('embeddingId', 255)->primary();
            $table->string('userId', 255);
			$table->timestamps();
			
			$table->foreign('userId')->references('userId')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
			$table->index(['embeddingId']);
        });
		
		DB::statement("ALTER TABLE embeddings ADD `content` MEDIUMBLOB AFTER `userId`");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('embeddings', function (Blueprint $table) {
			$table->dropForeign(['userId']);
		});
		
        Schema::dropIfExists('embeddings');
    }
}
