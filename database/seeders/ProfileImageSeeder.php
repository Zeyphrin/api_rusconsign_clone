<?php

namespace Database\Seeders;

use App\Models\Mitra;
use App\Models\ProfileImage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1); // Change to the authenticated user's ID
        $mitra = Mitra::find(1); // Change to the mitra's ID

        if ($user && $mitra) {
            ProfileImage::create([
                'user_id' => $user->id,
                'mitra_id' => $mitra->id,
                'bio' => 'Sample bio',
            ]);
        }
    }
}
