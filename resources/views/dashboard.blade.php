@extends('layouts.app')

@section('page')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-sm-3 order-sm-2">
                <div style="">
                    <div class="ts-card-dark" id="db-ticket-chart">
                        <div class="table-header fg-yellow">
                            <h5>Tickets Breakdown</h5>
                        </div>
                        <canvas id="dbTicketsBreakdown" width="300" height="300" role="img" class="no-mobile"></canvas>
                        <div class="p-2 center">
                            <span class="fs-xl fg-dark">
                                {{ array_sum($chartData['counts']) }}
                            </span><br>
                            <span>OVERALL</span>
                        </div>
                        <script>
                            const ctx   =   document.getElementById("dbTicketsBreakdown").getContext("2d");
                            const dbtb  =   new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: {!! json_encode($chartData['labels'], JSON_HEX_TAG) !!},
                                    datasets: [{
                                        label: 'Tickets Breakdown',
                                        data: {!! json_encode($chartData['counts'], JSON_HEX_TAG) !!},
                                        backgroundColor: {!! json_encode($chartData['colors'], JSON_HEX_TAG) !!},
                                        borderColor: {!! json_encode($chartData['colors'], JSON_HEX_TAG) !!},
                                        borderWidth: 1,
                                        hoverOffset: 3,
                                    }]
                                },
                                options: {
                                    layout: {
                                        padding: {
                                            top: 5,
                                            right: 5,
                                            bottom: 5,
                                            left: 5
                                        }
                                    },
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        title: {
                                            display: false,
                                            text: 'All Tickets'
                                        }
                                    }
                                }
                                
                            });
                        </script>
                    </div>
                </div>
            </div>
            <div class="col-md order-md-1">
                <div class="mb-3 flex-grow-1">
                    <div class="ts-card-dark mb-4">
                        <div class="table-header fg-yellow">
                            <h5>Unassigned Tickets</h5>
                        </div>
                        <table class="table table-borderless ts-table">
                            <thead>
                                <th>Key</th>
                                <th class="no-mobile">Priority</th>
                                <th>Title</th>
                                <th class="no-mobile">Reporter</th>
                                <th class="right no-mobile">Created</th>
                            </thead>
                            <tbody>
                                @if (count($gpUnassigned) > 0)
                                    @foreach ($gpUnassigned as $unassigned)
                                        @php
                                            $overdue    =   '';
                                            $createdAt  =   \Carbon\Carbon::create($unassigned->created_at);
    
                                            if ($createdAt < $dueDate) {
                                                $overdue    =   'overdue';
                                            }
                                        @endphp
                                        <tr class="{{ $overdue }} ">
                                            <td>
                                                <span>
                                                    <a href="/tickets/{{ $unassigned->tkey }}/edit" class="link-yellow"
                                                        @if ($overdue == 'overdue') data-bs-toggle="tooltip" title="Overdue" @endif
                                                        data-bs-placement="bottom">
                                                        {{ $unassigned->tkey }}
                                                    </a>
                                                </span>
                                            </td>
                                            <td class="no-mobile">
                                                {{ Str::ucfirst($unassigned->priority) }}
                                            </td>
                                            <td>
                                                {{ $unassigned->title }}
                                            </td>
                                            <td class="no-mobile">
                                                <a href="/users/{{ $unassigned->user->id }}" class="link-light">
                                                    {{ $unassigned->user->first_name . ' ' . $unassigned->user->last_name }}
                                                </a>
                                            </td>
                                            <td class="right no-mobile">
                                                {{ \Carbon\Carbon::create($unassigned->created_at)->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">
                                            No unassigned tickets.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="pl-3 pr-3 d-flex justify-content-between">
                            <span class="fg-forest">
                                @if (count($gpUnassigned) > 0)
                                    {{ 'Showing ' . $gpUnassigned->total() . ' tickets' }}
                                @endif
                            </span>
                            <span>
                                {{ $gpUnassigned->links() }}
                            </span>
                        </div>
                    </div>
                    <div class="ts-card-dark">
                        <div class="table-header fg-yellow">
                            <h5>My Tickets</h5>
                        </div>
                        <table class="table table-borderless ts-table">
                            <thead class="fg-white">
                                <th class="pt-3">Key</th>
                                <th class="no-mobile">Priority</th>
                                <th class="no-mobile">Status</th>
                                <th>Description</th>
                                <th class="no-mobile">Reporter</th>
                                <th class="right no-mobile">Created</th>
                            </thead>
                            <tbody>
                                @if (count($myTickets) > 0)
                                    @foreach ($myTickets as $mytk)
                                        @php
                                            $theme      =   '';
                                            $overdue    =   '';
    
                                            $createdAt  =   \Carbon\Carbon::create($mytk->created_at);
                                            $tkstat     =   ucwords(Str::replace('-', ' ', $mytk->status));
    
                                            switch ($mytk->status) {
                                                case 'new':
                                                    $theme  =   'primary';
                                                    break;
                                                case 'in-progress':
                                                    $theme  =   'warning';
                                                    break;
                                                case 'on-hold':
                                                    $theme  =   'purple';
                                                    break;
                                                case 'resolved':
                                                    $theme  =   'success';
                                                    break;
                                                default:
                                                    $theme  =   'secondary';
                                                    break;
                                            }
                                            
                                            // if ($createdAt < $dueDate) {
                                            //     $theme      =   'pumpkin';
                                            //     $overdue    =   'overdue';
                                            // }
                                        @endphp
                                        <tr class="{{ $overdue }}">
                                            <td>
                                                <span>
                                                    <a href="/tickets/{{ $mytk->tkey }}/edit" class="link-yellow"
                                                        @if ($overdue == 'overdue') data-bs-toggle="tooltip" title="Overdue" @endif
                                                        data-bs-placement="bottom">
                                                        {{ $mytk->tkey }}
                                                    </a>
                                                </span>
                                            </td>
                                            <td class="no-mobile">
                                                {{ Str::ucfirst($mytk->priority) }}
                                            </td>
                                            <td class="no-mobile">
                                                <span class="dot-dark dot-{{ $theme }}">
                                                    {{ $tkstat }}
                                                </span>
                                            </td>
                                            <td data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $mytk->title }}">
                                                @if (Str::length($mytk->title) > 100)
                                                    {{ Str::substr($mytk->title, 0, 100) . '...' }}
                                                @else
                                                    {{ $mytk->title }}
                                                @endif
                                            </td>
                                            <td class="no-mobile">
                                                <a href="/users/{{ $mytk->user->id }}" class="link-light">
                                                    {{ $mytk->user->first_name . ' ' . $mytk->user->last_name }}
                                                </a>
                                            </td>
                                            <td class="right no-mobile">
                                                {{ \Carbon\Carbon::create($mytk->created_at)->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">
                                            No tickets assigned to you.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="p-3 pt-0 d-flex justify-content-between">
                            <span class="fg-forest">
                                @if (count($myTickets) > 0)
                                    {{ 'Showing ' . $myTickets->total() . ' tickets' }}
                                @endif
                            </span>
                            <span>
                                {{ $myTickets->links() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
