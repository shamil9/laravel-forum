<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBestRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('best_replies', function (Blueprint $table) {
            $table->foreign('thread_id')
                ->references('id')->on('threads')
                ->onDelete('cascade');

            $table->foreign('reply_id')
                ->references('id')->on('replies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('best_replies', function (Blueprint $table) {
            //
        });
    }
}
