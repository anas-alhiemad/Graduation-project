<?php

namespace Database\Seeders;

use App\Models\WeekDay;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WeekDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = [
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
        ];

        foreach ($days as $day) {
            WeekDay::create(['name' => $day]);
        }
    }
}
