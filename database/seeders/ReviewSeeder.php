<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Illuminate\Support\Carbon;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'business_id' => 1,
                'client_name' => 'Nadia Briones Beltran',
                'client_phone' => '+34 600 123 456',
                'rating' => 3,
                'comment' => 'Dolor dolorem sint cumque est quasi similique aliquid.',
                'result' => '3 estrellas',
                'review_date' => Carbon::parse('2025-03-15'),
                'reviewed_at' => Carbon::parse('2025-03-15 07:32:21'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'María Fernanda Baquero-Borja',
                'client_phone' => '+34 600 222 333',
                'rating' => 5,
                'comment' => 'Pariatur nobis nesciunt sed veniam odit ullam esse.',
                'result' => '5 estrellas',
                'review_date' => Carbon::parse('2025-02-13'),
                'reviewed_at' => Carbon::parse('2025-02-13 19:16:09'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Maricela Lidia Codina Guardia',
                'client_phone' => '+34 600 987 654',
                'rating' => 4,
                'comment' => 'Sequi excepturi dolorum vel quidem in sint exercitationem.',
                'result' => '4 estrellas',
                'review_date' => Carbon::parse('2025-03-10'),
                'reviewed_at' => Carbon::parse('2025-03-10 18:23:58'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Juanito Uriarte Espejo',
                'client_phone' => '+34 688 222 111',
                'rating' => 2,
                'comment' => 'Quae ut corrupti rerum numquam autem necessitatibus.',
                'result' => '2 estrellas',
                'review_date' => Carbon::parse('2025-03-28'),
                'reviewed_at' => Carbon::parse('2025-03-28 16:06:04'),
            ],
            [
                'business_id' => 1,
                'client_name' => 'Ani Madrigal-Caparrós',
                'client_phone' => '+34 655 123 789',
                'rating' => 5,
                'comment' => 'Maxime rem error ex mollitia rerum officia quam.',
                'result' => '5 estrellas',
                'review_date' => Carbon::parse('2025-03-03'),
                'reviewed_at' => Carbon::parse('2025-03-03 15:20:43'),
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
