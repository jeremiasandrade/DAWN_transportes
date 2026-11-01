<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@dawn.test';

    public function run(): void
    {
        foreach (range(1, 5) as $clientNumber) {
            $clientEmail = sprintf('cliente%02d@dawn.test', $clientNumber);

            if (! User::query()->where('email', $clientEmail)->exists()) {
                User::factory()->client()->create([
                    'email' => $clientEmail,
                ]);
            }
        }

        if (! User::query()->where('email', self::ADMIN_EMAIL)->exists()) {
            User::factory()->administrator()->create([
                'email' => self::ADMIN_EMAIL,
            ]);
        }
    }
}
