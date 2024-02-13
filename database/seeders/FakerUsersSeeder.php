<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Faker\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Throwable;

class FakerUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $console = $this->command->getOutput();
        $amount = $console->ask('Koliko korisnika zelite da kreirate?', 5);
        $password = $console->ask('Koja je sifra za sve korisnike?', 'password');

        $faker = Factory::create();

//        $console->progressStart($amount);
//        $count = 0;
//        for($i=0; $i<$amount; $i++)
//        {
//            try {
//                User::create([
//                    "name" => $faker->name(),
//                    "email" => $faker->unique()->safeEmail(),
//                    "password" => Hash::make($password)
//                ]);
//                $count++;
//            } catch (Throwable $e) {
//            }
//            $console->progressAdvance();
//        }
//        $console->progressFinish();

        $time = Carbon::now();
        $pass = Hash::make($password);
        $users = array();
        for($i=0; $i<$amount; $i++)
        {
            $users[] = [
                "name" => $faker->name(),
                "email" => $faker->unique()->safeEmail(),
                "password" => $pass,
                'created_at' => $time,
                'updated_at' => $time
            ];
        }
        $count = count($users);
        User::insert($users);

        $console->info("Uspesno je kreirano $count korisnika");
    }
}
