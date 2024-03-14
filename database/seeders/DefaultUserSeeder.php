<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Throwable;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "ADMIN",
                "email" => "admin@admin.com",
                "password" => "adminadmin",
                "role" => "admin"
            ],
            [
                "name" => "USER",
                "email" => "user@user.com",
                "password" => "useruser"
            ]
        ];
        $amount = count($users);

        foreach ($users as $user)
        {
            try {
                User::create($user);
            } catch (Throwable $e) {
                $amount--;
            }
        }

        $this->command->getOutput()->info("Uspesno je kreirano $amount default korisnika");
    }
}
