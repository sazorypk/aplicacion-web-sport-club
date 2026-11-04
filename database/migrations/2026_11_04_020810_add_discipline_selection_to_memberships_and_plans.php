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
            $table->boolean('includes_all_disciplines')->default(false);
        });
        Schema::table('memberships', function (Blueprint $table) {
            $table->string('discipline')->nullable();
        });

        DB::table('plans')->where('name', 'Plan Premium')->update(['includes_all_disciplines' => true]);
        DB::table('plans')->where('name', 'Plan Premium')
            ->whereIn('description', ['Acceso ampliado a clases y zonas de entrenamiento.', 'Acceso a tres disciplinas y zonas de entrenamiento.'])
            ->update(['description' => 'Acceso a todas las disciplinas y zonas de entrenamiento.']);
    }

    public function down(): void
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropColumn('discipline');
        });
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('includes_all_disciplines');
        });
    }
};
