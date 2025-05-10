<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unconfirmed temporary reservations older than 48 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedSeate = \App\Models\SectionStudent::where('is_confirmed', false)
        ->where('created_at', '<=', now()->subHours(48))
        ->delete();
        \App\Models\CourseSection::where('id',$deletedSeate)->decrement('reservedSeats');

    $this->info("$deletedSeate expired reservations deleted.");
    }
}
