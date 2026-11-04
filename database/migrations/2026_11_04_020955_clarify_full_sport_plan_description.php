<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('name', 'Plan Full Sport')
            ->where('description', 'Acceso completo a las actividades del club.')
            ->update(['description' => 'Elige una disciplina y disfruta de las instalaciones del club.']);
    }

    public function down(): void
    {
        DB::table('plans')->where('name', 'Plan Full Sport')
            ->where('description', 'Elige una disciplina y disfruta de las instalaciones del club.')
            ->update(['description' => 'Acceso completo a las actividades del club.']);
    }
};
