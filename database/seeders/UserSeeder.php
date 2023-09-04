<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->times(10)->create();
        for ($i=1; $i <= 9 ; $i++) {
            User::factory()->create([

                'firstname' => 'a'."$i",
                'fathername' => 'a'."$i",
                'lastname' => 'a'."$i",
                'birthdate'  => '2000-03-'.'0'.$i ,
                //or  'birthdate' =>  '2000-01-0'.$i,
                'gender'     => fake()->randomElement(['male','female']),
                'phone'      => '09'.fake()->unique()-> randomNumber(8, true) ,
                'address' =>  'Damascus, Airplane Street , Lemon House',
                // 'address'    => fake()->city().' ,'.fake()->streetName() ,
                'email' => 'a'."$i".'@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),

            ]);
           }
    }
}
