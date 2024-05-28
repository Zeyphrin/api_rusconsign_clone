<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;
use App\Models\Product;
use App\Models\Mitra;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mitra = Mitra::inRandomOrder()->first();
        $mitraId = $mitra ? $mitra->id : Mitra::factory()->create()->id;

        return [
            'name_product' => $this->faker->name,
            'desc_product' => $this->faker->paragraph,
            'price_product' => $this->faker->randomNumber(6,true),
            'rating_product' => $this->faker->randomFloat(1, 1, 5),
            'mitra_id' => $mitraId,
            'image' => $this->faker->imageUrl(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
