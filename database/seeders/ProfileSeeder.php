<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Profile::factory()->create([
            'user_id' => 1,
            'first_name' => 'Mustafa',
            'last_name' => 'ElSayed',
            'username' => 'mustafaelsayedfouda',
            'avatar' => 'https://example.com/avatar.jpg',
            'bio' => 'This is a sample bio',
            'phone' => '0123456789',
            'address' => '123 Main St',
            'city' => 'Alexandria',
            'country' => 'Egypt',
            'birth_date' => '2001-09-28',
            'gender' => 'male',
        ]);
        Profile::factory(20)->create();
    }
}
