<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inboxes', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->default(0);
            $table->string('sender')->nullable();
            $table->integer('status')->default(0);
            $table->integer('transaction_id')->nullable();
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->longText('message')->nullable();
            $table->datetime('tanggal')->nullable();
            $table->integer('op')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inboxes');
    }
}
