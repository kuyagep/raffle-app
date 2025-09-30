<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $districts = [
            'Bansalan East',
            'Bansalan West',
            'Hagonoy I',
            'Hagonoy II',
            'Kiblawan North',
            'Kiblawan South',
            'Matanao I',
            'Matanao II',
            'Magsaysay North',
            'Magsaysay South',
            'Malalag',
            'Padada',
            'Sta. Cruz North',
            'Sta. Cruz South',
            'Sulop',
        ];

        $municipalities = [
            'Bansalan',
            'Hagonoy',
            'Kiblawan',
            'Matanao',
            'Magsaysay',
            'Malalag',
            'Padada',
            'Sta. Cruz',
            'Sulop',
        ];

        return [
            'district_division'   => $this->faker->randomElement($districts),
            'municipality'        => $this->faker->randomElement($municipalities),
            'full_name'           => $this->faker->name(),
            'sex'                 => $this->faker->randomElement(['Male', 'Female']),
            'school_office'          => $this->faker->company() . ' School',
            'email' => $this->faker->unique()->safeEmail(),
            'contact_number'      => $this->faker->phoneNumber(),
            'qr_code'           => uniqid(),
        ];
    }
}
