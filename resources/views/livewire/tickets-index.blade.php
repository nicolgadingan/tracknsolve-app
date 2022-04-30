<div class="card card-body border-round border-forest-light" style="overflow-x: auto;">
    <div class="d-flex justify-content-between mb-3 align-items-end">
        <h6>
            <b>All Tickets</b>
        </h6>
        <div class="search">
            <input type="text" class="form-control search-input" placeholder="Search..."
                name="" id="tk-search-ticket" wire:model="search">
        </div>
    </div>
    @include('plugins.messages')
    <table class="table table-hover">
        <thead class="bg-light">
            <th>Key</th>
            <th>Status</th>
            <th>Priority</th>
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
                            <a href="/tickets/{{ $ticket->tkey }}/edit" class="link-marine">
                                <strong>{{ $ticket->tkey }}</strong>
                            </a>
                        </td>
                        <td>
                            @php
                                $theme      =   '';
                                $tkstatus   =   ucwords(Str::replace('-', ' ', $ticket->status));
                                switch ($ticket->status) {
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
                                    case 'closed':
                                        $theme  =   'dark';
                                        break;
                                    default:
                                        $theme  =   'secondary';
                                        break;
                                }
                            @endphp
                            <span class="dot dot-{{ $theme }}">
                                {{ Str::ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td>
                            {{ Str::ucfirst($ticket->priority) }}
                        </td>
                        <td class="td-break">
                            {{ $ticket->title }}
                        </td>
                        <td>
                            {{ $ticket->group_name }}
                        </td>
                        <td>
                            {{ $ticket->reporter_fn . ' ' . $ticket->reporter_ln }}
                        </td>
                        <td>
                            @php
                                if ($ticket->assignee_fn != null ||
                                        $ticket->assignee_fn != '') {
                                    $assignee   =   $ticket->assignee_fn . ' ' . $ticket->assignee_ln;
                                } else {
                                    $assignee   =   '';
                                }
                            @endphp
                            {{ $assignee }}
                        </td>
                        <td class="right">
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
    <div class="mb-3 right">
        {{ $tickets->links() }}
    </div>
</div>