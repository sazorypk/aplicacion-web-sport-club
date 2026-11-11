<?php

namespace Database\Seeders;

use App\Models\AccountDeletionRequest;
use Illuminate\Database\Seeder;

class AccountDeletionRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local')) {
            AccountDeletionRequest::factory()->create();
        }
    }
}
