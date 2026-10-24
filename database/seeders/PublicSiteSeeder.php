<?php
namespace Database\Seeders;

use App\Models\Event;
use App\Models\News;
use Illuminate\Database\Seeder;

class PublicSiteSeeder extends Seeder
{
    public function run()
    {
        foreach ([
            'Annual Prize Giving Announced', 'New Science Laboratory Opened',
            'Scholarship Results Released', 'Inter-School Sports Meet Winners',
            'Tamil & Sinhala Literary Week', 'Term Test Timetable Published',
        ] as $i => $title) {
            News::create([
                'title' => $title,
                'excerpt' => 'Highlights from our school community this term.',
                'body' => "Full story for: {$title}.\n\nReplace this with real content from the admin side.",
                'published_at' => now()->subDays($i * 3),
            ]);
        }
        foreach ([
            ['Parents Meeting', 7], ['Annual Sports Day', 21], ['Science Exhibition', 35], ['Prize Giving', 60],
        ] as [$title, $days]) {
            Event::create([
                'title' => $title, 'description' => 'All parents and students are welcome.',
                'location' => 'School Hall', 'event_date' => now()->addDays($days), 'start_time' => '09:00',
            ]);
        }
    }
}
