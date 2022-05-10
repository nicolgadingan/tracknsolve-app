<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class DashboardTickets extends BaseChart
{
    public ?string  $name           =   'Tickets Breakdown';
    public ?string  $routeName      =   'dbTickets';
    public ?array   $middlewares    =   ['auth'];

    public $colors;

    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $tickets    =   Ticket::select(DB::raw('count(1) as tkcount')
                                    ,'status')
                                ->groupBy('status')
                                ->get();

        $this->colors   =   [];
        $labels         =   [];
        $counts         =   [];

        foreach ($tickets as $ticket) {
            $labels[]   =   ucwords($ticket->status);
            $counts[]   =   $ticket->tkcount;

            switch ($ticket->status) {
                case 'new':
                    $this->colors[]   =   '#34ace0';
                    break;
                case 'in-progress':
                    $this->colors[]   =   '#ffda79';
                    break;
                case 'on-hold':
                    $this->colors[]   =   '#bcc1c7';
                    break;
                case 'resolved':
                    $this->colors[]   =   '#33d9b2';
                    break;
            }
        }

        return Chartisan::build()
            ->labels($labels)
            ->dataset('Sample', $counts);
    }
}