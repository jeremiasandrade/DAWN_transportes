<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\UserAccountService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    private const ADMIN_EMAIL = 'admin@dawn.test';

    public function run(UserAccountService $accountService): void
    {
        foreach (range(1, 5) as $clientNumber) {
            $clientEmail = sprintf('cliente%02d@dawn.test', $clientNumber);

            if (! User::query()->where('email', $clientEmail)->exists()) {
                $accountService->createCustomer(User::factory()->raw([
                    'email' => $clientEmail,
                ]));
            }
        }

        if (! User::query()->where('email', self::ADMIN_EMAIL)->exists()) {
            $accountService->createAdministrator(User::factory()->raw([
                'email' => self::ADMIN_EMAIL,
            ]));
        }
    }
}
