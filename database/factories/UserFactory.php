<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            // 'email' => fake()->unique()->safeEmail(),


            'firstname'  => fake()-> firstName() ,
            'fathername' => fake()->word() , // firstNameMale()
            'lastname'   => fake()->lastName() ,
            'birthdate'  => fake()->date('Y-m-d', now()) ,
            'gender'     => fake()->randomElement(['male','female']),
            'phone'      => '09'.fake()->unique()-> randomNumber(8, true) ,
            'address'    => fake()->city().' ,'.fake()->streetName() ,
            'email'      => fake()->unique()->freeEmail() ,
            'password'   => 'password',

            'email_verified_at' => now(),
            'remember_token' => Str::random(10),


        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
