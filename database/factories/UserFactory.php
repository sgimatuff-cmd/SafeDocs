<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'                => fake()->name(),
            'email'               => fake()->unique()->safeEmail(),
            'email_verificado_em' => now(),
            'palavra_passe'       => Hash::make('password'),
            'e_administrador'     => false,
            'remember_token'      => Str::random(10),
        ];
    }
}
