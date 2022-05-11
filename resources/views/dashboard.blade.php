@extends('layouts.app')

@section('page')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex">
            <div class="mb-3 flex-grow-1 pr-4">
                <div class="p-3 pb-2">
                    <span class="fg-marine fs-sm">UNASSIGNED TICKETS</span>
                </div>
                <div class="card card-body mb-4 borderless border-round p-0 shadow-sm">
                    <table class="table table-borderless table-hover">
                        <thead class="fg-white bg-marine-dark">
                            <th class="pt-3">Key</th>
                            <th>Title</th>
                            <th>Reporter</th>
                            <th class="right">Created</th>
                        </thead>
                        <tbody>
                            @if (count($gpUnassigned) > 0)
                                @foreach ($gpUnassigned as $unassigned)
                                    <tr>
                                        <td>
                                            <strong>
                                                <a href="/tickets/{{ $unassigned->tkey }}/edit" class="link-marine">{{ $unassigned->tkey }}</a>
                                            </strong>
                                        </td>
                                        <td>
                                            {{ $unassigned->title }}
                                        </td>
                                        <td>
                                            {{ $unassigned->user->first_name . ' ' . $unassigned->user->last_name }}
                                        </td>
                                        <td class="right">
                                            {{ \Carbon\Carbon::create($unassigned->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        No unassigned tickets.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="p-3 pb-2">
                    <span class="fg-marine fs-sm">MY TICKETS</span>
                </div>
                <div class="card card-body border-round borderless p-0 shadow-sm">
                    <table class="table table-borderless table-hover">
                        <thead class="bg-marine-dark fg-white">
                            <th class="pt-3">Key</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Reporter</th>
                            <th class="right">Created</th>
                        </thead>
                        <tbody>
                            @if (count($myTickets) > 0)
                                @foreach ($myTickets as $mytk)
                                    <tr>
                                        <td>
                                            <strong>
                                                <a href="/tickets/{{ $mytk->tkey }}/edit" class="link-marine">{{ $mytk->tkey }}</a>
                                            </strong>
                                        </td>
                                        <td>
                                            {{ Str::ucfirst($mytk->priority) }}
                                        </td>
                                        <td>
                                            @php
                                                $theme  =   '';
                                                $tkstat =   ucwords(Str::replace('-', ' ', $mytk->status));
                                                switch ($mytk->status) {
                                                    case 'new':
                                                        $theme  =   'primary';
                                                        break;
                                                    case 'in-progress':
                                                        $theme  =   'warning';
                                                        break;
                                                    case 'on-hold':
                                                        $theme  =   'secondary';
                                                        break;
                                                    case 'resolved':
                                                        $theme  =   'success';
                                                        break;
                                                    default:
                                                        $theme  =   'secondary';
                                                        break;
                                                }
                                            @endphp
                                            <span class="dot dot-{{ $theme }}">
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
                                        <td>
                                            {{ $mytk->user->first_name . ' ' . $mytk->user->last_name }}
                                        </td>
                                        <td class="right">
                                            {{ \Carbon\Carbon::create($mytk->created_at)->diffForHumans() }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        You do not have assigned tickets.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="width: 25%; max-width: 350px;" class="pl-2">
                <div class="p-3 pb-2">
                    <span class="fg-marine fs-sm">TICKETS BREAKDOWN</span>
                </div>
                <div class="card card-body border-round borderless shadow-sm" id="db-ticket-chart">
                    <canvas id="dbTicketsBreakdown" width="300" height="300" role="img"></canvas>
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
    </div>
@endsection
