<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedTinyInteger('discipline_limit')->default(1);
        });
        Schema::table('memberships', function (Blueprint $table) {
            $table->json('disciplines')->nullable();
        });
        DB::table('plans')->where('name', 'Plan Full Sport')->update(['discipline_limit' => 3]);
        DB::table('plans')->where('name', 'Plan Full Sport')
            ->whereIn('description', ['Acceso completo a las actividades del club.', 'Elige una disciplina y disfruta de las instalaciones del club.'])
            ->update(['description' => 'Acceso a tres disciplinas e instalaciones del club.']);
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('disciplines');
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('discipline_limit');
        });
    }
};
