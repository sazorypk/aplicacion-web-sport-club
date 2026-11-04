<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('name', 'Plan Premium')->update([
            'includes_all_disciplines' => false,
            'discipline_limit' => 3,
            'description' => 'Acceso a tres disciplinas y zonas de entrenamiento.',
        ]);
        DB::table('plans')->where('name', 'Plan Full Sport')->update([
            'includes_all_disciplines' => true,
            'description' => 'Acceso a todas las disciplinas e instalaciones del club.',
        ]);
    }

    public function down(): void
    {
        // The previous plan configuration may have been customized; do not overwrite it on rollback.
    }
};
