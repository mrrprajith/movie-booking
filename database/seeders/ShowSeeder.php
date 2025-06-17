<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Show;
use App\Models\Seat;

class ShowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run() {
         // Example: create 2 shows
         $shows = [
             ['show_date' => '2025-06-18', 'show_time' => '18:00:00'],
             ['show_date' => '2025-06-18', 'show_time' => '21:00:00'],
         ];

         foreach ($shows as $showData) {
             $show = Show::create($showData);

             // Create 20 seats for each show
             foreach (range(1, 20) as $i) {
                 Seat::create([
                     'seat_number' => 'A' . $i, // A1, A2, A3...
                     'show_id' => $show->id,
                 ]);
             }
         }
     }
}
