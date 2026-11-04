<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('sedes')->where('name', 'Sede Centro')->update(['is_active' => false]);
        DB::table('sedes')->where('name', 'Sede Norte')->whereNull('address')->update(['address' => 'Calle 52 # 48-35, Bello, Antioquia']);
        DB::table('sedes')->where('name', 'Sede Sur')->whereNull('address')->update(['address' => 'Carrera 43 # 38 Sur-25, Envigado, Antioquia']);
    }

    public function down(): void
    {
        // Existing memberships and location details must be preserved on rollback.
    }
};
