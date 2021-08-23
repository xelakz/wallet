<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->string('uuid', 32);
            $table->integer('currency_id');
            $table->double('balance', 12, 2);
            $table->timestamps();

            $table->foreign('uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade');

            $table->foreign('currency_id')
                ->references('id')->on('currencies')
                ->onDelete('cascade');

            $table->primary(['uuid', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
