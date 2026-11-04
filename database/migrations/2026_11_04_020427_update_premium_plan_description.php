<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')
            ->where('name', 'Plan Premium')
            ->where('description', 'Acceso ampliado a clases y zonas de entrenamiento.')
            ->update(['description' => 'Acceso a tres disciplinas y zonas de entrenamiento.']);
    }

    public function down(): void
    {
        DB::table('plans')
            ->where('name', 'Plan Premium')
            ->where('description', 'Acceso a tres disciplinas y zonas de entrenamiento.')
            ->update(['description' => 'Acceso ampliado a clases y zonas de entrenamiento.']);
    }
};
