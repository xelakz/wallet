<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('token', 32);
            $table->string('uuid', 32)->nullable();
            $table->text('scopes');
            $table->string('client_id', 32);
            $table->boolean('revoked')->default(false);
            $table->timestamps();
            $table->datetime('expires_at');

            $table->primary('token');

            $table->foreign('uuid')
                ->references('uuid')->on('users')
                ->onDelete('cascade');

            $table->foreign('client_id')
                ->references('client_id')->on('oauth_clients')
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
        Schema::dropIfExists('oauth_access_tokens');
    }
}
