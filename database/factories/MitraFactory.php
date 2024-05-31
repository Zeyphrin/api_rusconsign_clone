<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mitra;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mitra>
 */
class MitraFactory extends Factory
{
    protected $model = Mitra::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image_profile' => $this->faker->imageUrl(),
            'nama_lengkap' => $this->faker->name,
            'nama_toko' => $this->faker->company,
            'nis' => $this->faker->unique()->numberBetween(100000, 999999), // Adjust the range as needed
            'no_dompet_digital' => $this->faker->phoneNumber,
            'image_id_card' => $this->faker->imageUrl(),
            'status' => $this->faker->randomElement(['accepted', 'pending']), // Example status values
            'pengikut' => $this->faker->optional()->numberBetween(0, 10000), // Optional field
            'jumlah_jasa' => $this->faker->numberBetween(0, 100), // Default to 0
            'jumlah_product' => $this->faker->numberBetween(0, 100), // Default to 0
            'penilaian' => $this->faker->randomFloat(2, 0, 5), // Default to 0, range between 0 and 5
            'email' => $this->faker->unique()->safeEmail,
            'remember_token' => $this->faker->sha256,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
