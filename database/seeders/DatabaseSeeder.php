<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Place;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        // Create places
        $place1 = Place::create([
            'name' => 'Grand Hotel',
            'description' => 'A luxurious 5-star hotel in the heart of the city',
            'type' => 'hotel',
            'address' => '123 Main Street, City Center',
            'lat' => 40.7128,
            'lng' => -74.0060,
            'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
        ]);

        $place2 = Place::create([
            'name' => 'Mountain View Restaurant',
            'description' => 'Fine dining with panoramic mountain views',
            'type' => 'restaurant',
            'address' => '456 Hill Road, Mountain View',
            'lat' => 40.7589,
            'lng' => -73.9851,
            'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800',
        ]);

        $place3 = Place::create([
            'name' => 'Adventure Park',
            'description' => 'Thrilling rides and family fun activities',
            'type' => 'entertainment',
            'address' => '789 Adventure Lane',
            'lat' => 40.7614,
            'lng' => -73.9776,
            'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
        ]);

        // Create tours
        $tour1 = Tour::create([
            'title' => 'City Walking Tour',
            'description' => 'Explore the historic downtown area with a local guide',
            'place_id' => $place1->id,
            'price' => 50.00,
            'duration_hours' => 3,
        ]);

        $tour2 = Tour::create([
            'title' => 'Mountain Hiking Adventure',
            'description' => 'Full-day hiking tour through scenic mountain trails',
            'place_id' => $place2->id,
            'price' => 120.00,
            'duration_hours' => 8,
        ]);

        $tour3 = Tour::create([
            'title' => 'Theme Park Experience',
            'description' => 'Full access to all rides and attractions',
            'place_id' => $place3->id,
            'price' => 75.00,
            'duration_hours' => 6,
        ]);

        // Create bookings
        Booking::create([
            'user_id' => $user1->id,
            'bookable_type' => 'App\Models\Tour',
            'bookable_id' => $tour1->id,
            'date' => now()->addDays(7)->format('Y-m-d'),
            'guests' => 2,
            'total_price' => 100.00,
            'status' => 'confirmed',
        ]);

        Booking::create([
            'user_id' => $user2->id,
            'bookable_type' => 'App\Models\Tour',
            'bookable_id' => $tour2->id,
            'date' => now()->addDays(14)->format('Y-m-d'),
            'guests' => 1,
            'total_price' => 120.00,
            'status' => 'pending',
        ]);

        Booking::create([
            'user_id' => $user1->id,
            'bookable_type' => 'App\Models\Place',
            'bookable_id' => $place1->id,
            'date' => now()->addDays(5)->format('Y-m-d'),
            'guests' => 2,
            'total_price' => 200.00,
            'status' => 'confirmed',
        ]);

        // Create reviews
        Review::create([
            'user_id' => $user1->id,
            'reviewable_type' => 'App\Models\Place',
            'reviewable_id' => $place1->id,
            'rating' => 5,
            'comment' => 'Excellent service and beautiful rooms!',
        ]);

        Review::create([
            'user_id' => $user2->id,
            'reviewable_type' => 'App\Models\Tour',
            'reviewable_id' => $tour1->id,
            'rating' => 4,
            'comment' => 'Great tour guide, very informative.',
        ]);

        Review::create([
            'user_id' => $user1->id,
            'reviewable_type' => 'App\Models\Place',
            'reviewable_id' => $place2->id,
            'rating' => 5,
            'comment' => 'Amazing food and stunning views!',
        ]);

        Review::create([
            'user_id' => $user2->id,
            'reviewable_type' => 'App\Models\Tour',
            'reviewable_id' => $tour2->id,
            'rating' => 5,
            'comment' => 'Best hiking experience ever!',
        ]);

        $this->command->info('Sample data seeded successfully!');
        $this->command->info('Users: 2');
        $this->command->info('Places: 3');
        $this->command->info('Tours: 3');
        $this->command->info('Bookings: 3');
        $this->command->info('Reviews: 4');
    }
}
