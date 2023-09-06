<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // User::factory()->times(10)->create();
        for ($i=1; $i <= 9 ; $i++) {
            Doctor::factory()->create([

                'specialist_on' => 'specialization #'."$i",
                'certifactions' => 'certification #'."$i",
                'location' => "Damascus, Airplane Street , Lemon House",
                'image'  => '2000-03-'.'0'.$i.'.jpg' ,
                // 'birthdate'     => fake()->randomElement(['2000-05-05','2020-02-02']),
                'user_id' => User::all()->unique()->random()->id ,

            ]);
           }

    }
}
