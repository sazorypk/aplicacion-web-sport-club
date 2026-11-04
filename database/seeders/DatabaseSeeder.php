<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Sede;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => "Plan B\u{00E1}sico", 'description' => 'Acceso a una disciplina y uso de la sede elegida.', 'price' => 89900],
            ['name' => 'Plan Premium', 'description' => 'Acceso a todas las disciplinas y zonas de entrenamiento.', 'price' => 149900, 'includes_all_disciplines' => true],
            ['name' => 'Plan Full Sport', 'description' => 'Acceso a tres disciplinas e instalaciones del club.', 'price' => 219900, 'discipline_limit' => 3],
        ] as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan + ['is_active' => true]);
        }

        foreach ([
            ['name' => 'Sede Norte', 'address' => 'Calle 52 # 48-35, Bello, Antioquia'],
            ['name' => 'Sede Sur', 'address' => 'Carrera 43 # 38 Sur-25, Envigado, Antioquia'],
        ] as $sede) {
            Sede::firstOrCreate(['name' => $sede['name']], $sede + ['is_active' => true]);
        }
    }
}
