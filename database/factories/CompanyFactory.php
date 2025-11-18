<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
class CompanyFactory extends Factory
{
    // Modello dei dati falsi
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company,
            'address' => $this->faker->streetAddress,
            'zip_code' => $this->faker->postcode,
            'city' => $this->faker->city,
            'province' => $this->faker->stateAbbr,
            'region' => $this->faker->state,
            'email' => $this->faker->unique()->companyEmail,
        ];
    }
}
