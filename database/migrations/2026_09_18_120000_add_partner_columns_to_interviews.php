<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('interviews')) {
            return;
        }

        Schema::table('interviews', function (Blueprint $table) {
            if (! Schema::hasColumn('interviews', 'partner_media')) {
                $table->string('partner_media')->nullable()->after('interviewee_affiliation');
            }
            if (! Schema::hasColumn('interviews', 'partner_writer')) {
                $table->string('partner_writer')->nullable()->after('partner_media');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('interviews')) {
            return;
        }

        Schema::table('interviews', function (Blueprint $table) {
            if (Schema::hasColumn('interviews', 'partner_writer')) {
                $table->dropColumn('partner_writer');
            }
            if (Schema::hasColumn('interviews', 'partner_media')) {
                $table->dropColumn('partner_media');
            }
        });
    }
};
