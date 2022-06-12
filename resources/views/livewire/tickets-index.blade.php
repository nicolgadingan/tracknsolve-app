<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm">
            <div class="row">
                <div class="col-sm-auto mb-2">
                    <div class="search">
                        <input type="text" class="form-control search-input borderless" placeholder="Search..."
                            name="" id="tk-search-ticket" wire:model="search">
                    </div>
                </div>
                <div class="col-sm-auto mb-2">
                    <div class="has-icon has-icon-start">
                        <select class="form-select borderless border-round has-icon-form" wire:model="filter">
                            <option value="">All</option>
                            @if (count($statuses) > 0)
                                @foreach ($statuses as $filter)
                                    <option value="{{ $filter->status }}">{{ Str::ucfirst($filter->status) }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="has-icon-this">
                            <i class="bi bi-clipboard-check fs-re"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-auto mb-2 right">
            <div id="tk-group-actions">
                <a href="/tickets/create" class="btn btn-marine shadow">
                    New Ticket
                </a>
            </div>
        </div>
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="card card-body borderless border-round shadow-sm p-0" style="overflow-x: auto;">
                <table class="table table-borderless table-hover">
                    <thead class="bg-marine-dark fg-white">
                        <td class="pt-3">Key</td>
                        <td>Status</td>
                        <td class="no-mobile">Priority</td>
                        <td>Title</td>
                        <td class="no-mobile">Group</td>
                        <td class="no-mobile">Assignee</td>
                        <td class="no-mobile">Reporter</td>
                        <td class="right no-mobile">Created</td>
                    </thead>
                    <tbody>
                        @if (count($tickets) > 0)
                            @foreach ($tickets as $ticket)
                                @php
                                    $dateDue    =   \Carbon\Carbon::now()->subDays($dueDays);
                                    $theme      =   '';
                                    $recStatus  =   '';
                                    $tkstatus   =   ucwords(Str::replace('-', ' ', $ticket->status));
                                    $forDueChk  =   false;
                                    switch ($ticket->status) {
                                        case 'new':
                                            $theme      =   'primary';
                                            $forDueChk  =   true;
                                            break;
                                        case 'in-progress':
                                            $theme      =   'warning';
                                            $forDueChk  =   true;
                                            break;
                                        case 'on-hold':
                                            $theme      =   'purple';
                                            $forDueChk  =   true;
                                            break;
                                        case 'resolved':
                                            $theme      =   'success';
                                            break;
                                        case 'closed':
                                            $theme      =   'dark';
                                            break;
                                        default:
                                            $theme      =   'secondary';
                                            break;
                                    }

                                    if ($forDueChk == true &&
                                        $ticket->created < $dateDue) {

                                        $theme      =   'pumpkin';
                                        $recStatus  =   'overdue';
                                    }
                                @endphp
                                <tr class="{{ $recStatus }}">
                                    <td>
                                        <a href="/tickets/{{ $ticket->tkey }}/edit" class="link-marine"
                                            @if ($recStatus == 'overdue') data-bs-toggle="tooltip" title="Overdue" @endif
                                            data-bs-placement="bottom">
                                            <strong>{{ $ticket->tkey }}</strong>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="dot dot-{{ $theme }}">
                                            {{ Str::ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td class="no-mobile">
                                        {{ Str::ucfirst($ticket->priority) }}
                                    </td>
                                    <td class="td-break" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ticket->title }}">
                                        <span>
                                            @if (Str::length($ticket->title) > 100)
                                                {{ Str::substr($ticket->title, 0, 100) . '...' }}
                                            @else
                                                {{ $ticket->title }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="no-mobile">
                                        {{ $ticket->group_name }}
                                    </td>
                                    <td class="no-mobile">
                                        @php
                                            if ($ticket->assignee_fn != null ||
                                                    $ticket->assignee_fn != '') {
                                                $assignee   =   $ticket->assignee_fn . ' ' . $ticket->assignee_ln;
                                            } else {
                                                $assignee   =   '';
                                            }
                                        @endphp
                                        <a href="/users/{{ $ticket->assignee_id }}" class="link-marine">
                                            {{ $assignee }}
                                        </a>
                                    </td class="no-mobile">
                                    <td class="no-mobile">
                                        <a href="/users/{{ $ticket->reporter_id }}" class="link-marine">
                                            {{ $ticket->reporter_fn . ' ' . $ticket->reporter_ln }}
                                        </a>
                                    </td>
                                    <td class="right no-mobile">
                                        {{ \Carbon\Carbon::create($ticket->created)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    No tickets found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="p-3 pt-0 d-flex justify-content-between">
                    <span class="fg-forest">
                        {{ 'Showing ' . $tickets->total() . ' tickets' }}
                    </span>
                    <span>
                        {{ $tickets->links() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
