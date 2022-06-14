<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\Ticket;
use App\Http\Controllers\Utils;
use App\Notifications\ReportPublicId;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Tickets - Auto close of resolved tickets based on
         * config TK_AUTO_X_DAYS
         */
        $schedule->call(function() {
            $ticket =   new Ticket();
            $ticket->autoClose();
        })
        ->everyFiveMinutes();

        /**
         * Tickets - Delete unused ticket_id reservations
         */
        $schedule->call(function() {
            $ticket =   new Ticket();
            $ticket->cleanupReservations();
        })
        ->daily();

        /**
         * Tickets - Delete unused ticket attachments
         */
        $schedule->call(function() {
            $utils      =   new Utils;
            $utils->attCleanup();
        })
        ->daily();

        /**
         * Delete expired reset-password tokens
         */
        $schedule->command('auth:clear-resets')->everyFiveMinutes();

        /**
         * Generate public file access identifier
         */
        $schedule->call(function() {
            $utils      =   new Utils;
            $utils->createPublicId();
        })
        ->everyTwoHours();

        /**
         * Check public access key file
         */
        $schedule->call(function() {
            $utils      =   new Utils;
            $accessible =   $utils->checkPublicId();

            if (!$accessible) {
                Notification::route('mail', env('MAIL_CALL_THE_GUY', 'mgadingan@tracknsolve'))
                            ->notify(new ReportPublicId(null));
            }

        })
        ->everyThirtyMinutes();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
