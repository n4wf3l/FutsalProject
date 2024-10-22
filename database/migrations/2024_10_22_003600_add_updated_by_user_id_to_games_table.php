<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('games', function (Blueprint $table) {
        $table->unsignedBigInteger('updated_by_user_id')->nullable()->after('away_score');
        $table->foreign('updated_by_user_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('games', function (Blueprint $table) {
        $table->dropForeign(['updated_by_user_id']);
        $table->dropColumn('updated_by_user_id');
    });
}
};
