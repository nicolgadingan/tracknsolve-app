@extends('layouts.app')

@section('page')
    Tickets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-sm">
            
        </div>
        <div class="col-sm right">
            <a href="/tickets/create" class="btn btn-marine shadow">
                New Ticket
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Tickets</b>
                </h6>
                <table class="table table-hover">
                    <thead>
                        <th>Key</th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Group</th>
                        <th>Reporter</th>
                        <th>Assignee</th>
                        <th class="right">Created</th>
                    </thead>
                    <tbody>
                        @if (count($tickets) > 0)
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                       {{ $ticket->tkey }} 
                                    </td>
                                    <td>
                                        {{ Str::ucfirst($ticket->status) }}
                                    </td>
                                    <td>
                                        {{ $ticket->title }}
                                    </td>
                                    <td>
                                        {{ $ticket->assignment->name }}
                                    </td>
                                    <td>
                                        {{ $ticket->user->first_name . ' ' . $ticket->user->last_name }}
                                    </td>
                                    <td>
                                        @php
                                            if ($ticket->assignee != null ||
                                                    $ticket->assignee != '') {
                                                $assignee   =   $ticket->assignedTo->first_name . ' ' . $ticket->assignedTo->last_name;
                                            } else {
                                                $assignee   =   '';
                                            }
                                        @endphp
                                        {{ $assignee }}
                                    </td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($ticket->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    No tickets.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection