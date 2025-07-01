<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Sports;
use App\Models\Venue;
use App\Models\Field;
use App\Models\Gallery;
use App\Models\VenueSports;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        User::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'full_name' => 'Admin User',
            'phone_number' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Seed sports
        $futsal = Sports::create([
            'name' => 'Futsal',
        ]);

        // Seed venues
        $hjMuriyah = Venue::create([
            'name' => 'Futsal Hj. Muriyah',
            'slug' => Str::slug('Futsal Hj Muriyah'),
            'image' => 'venue.jpg',
            'large_image' => 'field1.jpg',
            'city' => 'Slawi',
        ]);

        // Seed venue_sports
        VenueSports::create([
            'venue_id' => $hjMuriyah->id,
            'sport_id' => $futsal->id,
        ]);

        // Seed fields
        Field::create([
            'name' => 'Lapangan 1',
            'image' => 'field1.jpg',
            'venue_id' => $hjMuriyah->id,
        ]);

        Field::create([
            'name' => 'Lapangan 2',
            'image' => 'field2.jpg',
            'venue_id' => $hjMuriyah->id,
        ]);

        $galleryImages = [
            'gallery_field1.jpg',
            'gallery_field2.jpg',
            'gallery2_field1.jpg',
            'gallery2_field2.jpg',
            'gallery.jpg',
            'gallery2.jpg',
            'gallery3.jpg',
            'gallery4.jpg',
            'gallery5.jpg',
            'gallery6.jpg',
        ];

        foreach ($galleryImages as $image) {
            Gallery::create([
                'venue_id' => $hjMuriyah->id,
                'image' => $image,
            ]);
        }
    }
}