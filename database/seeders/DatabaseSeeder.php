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
            ['name' => 'Plan Premium', 'description' => 'Acceso ampliado a clases y zonas de entrenamiento.', 'price' => 149900],
            ['name' => 'Plan Full Sport', 'description' => 'Acceso completo a las actividades del club.', 'price' => 219900],
        ] as $plan) {
            Plan::firstOrCreate(['name' => $plan['name']], $plan + ['is_active' => true]);
        }

        foreach (['Norte', 'Centro', 'Sur'] as $name) {
            Sede::firstOrCreate(['name' => 'Sede '.$name], ['is_active' => true]);
        }
    }
}
