@extends('layouts.app')

@section('page')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card card-body mb-4 border-round border-forest-light">
                    <h6>
                        <b>Unassigned Tickets</b>
                    </h6>
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <th>Key</th>
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
                <div class="card card-body border-round border-forest-light">
                    <h6>
                        <b>My Tickets</b>
                    </h6>
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <th>Key</th>
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
                                        <td>
                                            {{ $mytk->title }}
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
            @php
                $ua =   0;
                $ip =   0;
                $oh =   0;
                $rs =   0;

                foreach ($tkSummary as $summary) {
                    switch ($summary->status) {
                        case 'new':
                            $ua =   $summary->tkcount;
                            break;
                        case 'in-progress':
                            $ip =   $summary->tkcount;
                            break;
                        case 'on-hold':
                            $oh =   $summary->tkcount;
                            break;
                        case 'resolved':
                            $rs =   $summary->tkcount;
                            break;
                    }
                }
            @endphp
            <div class="col-sm">
                <div class="card card-body border-round border-forest-light bg-forest">
                    <h6 class="fg-white">
                        Breakdown
                    </h6>
                    <a href="#unassigned" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-primary">
                                <i class="bi bi-clipboard-fill"></i>
                                Unassigned
                            </span>
                            <span>{{ $ua }}</span>
                        </div>
                    </a>
                    <a href="#in-progress" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-warning">
                                <i class="bi bi-clipboard-plus-fill"></i>
                                In Progress
                            </span>
                            <span>{{ $ip }}</span>
                        </div>
                    </a>
                    <a href="#on-hold" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-secondary">
                                <i class="bi bi-clipboard-minus-fill"></i>
                                On Hold
                            </span>
                            <span>{{ $oh }}</span>
                        </div>
                    </a>
                    <a href="#resolved" class="btn btn-light mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fg-success">
                                <i class="bi bi-clipboard-check-fill"></i>
                                Resolved
                            </span>
                            <span>{{ $rs }}</span>
                        </div>
                    </a>
                    <div class="mb-3 right">
                        <a href="/tickets/create" class="btn btn-marine shadow">
                            New Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
