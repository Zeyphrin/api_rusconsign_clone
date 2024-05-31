<?php

namespace Database\Seeders;

use App\Models\Mitra;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(10)->create();
        Product::factory(10)->create();
        Mitra::factory()->count(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $mitras = Mitra::all();

        // Update email for each Mitra based on User
        foreach ($mitras as $mitra) {
            $user = User::find($mitra->user_id);
            if ($user) {
                $mitra->update(['email' => $user->email]);
            }
        }
    }
}
