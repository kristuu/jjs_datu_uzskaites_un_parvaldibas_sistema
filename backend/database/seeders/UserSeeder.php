<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberUtil;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // generate 50 dummy users
        for ($i = 0; $i < 50; $i++) {
            $phoneNumber = $faker->e164PhoneNumber;
            $birthdate = $faker->dateTimeThisCentury->format('Y-m-d');

            DB::table('users')->insert([
                'person_code' => $faker->unique()->numerify('###########'),
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'birthdate' => $birthdate,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),   // using 'password' as a generic password for all seeded users
                'phone' => $phoneNumber,
                'iban_code' => $faker->iban('LV')
            ]);
        }
    }
}
