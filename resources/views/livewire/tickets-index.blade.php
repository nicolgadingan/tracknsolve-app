<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <div id="tk-group-updates" class="d-flex">
            <div class="mr-3">
                <div class="search" style="width: 300px;">
                    <input type="text" class="form-control search-input borderless" placeholder="Search..."
                        name="" id="tk-search-ticket" wire:model="search">
                </div>
            </div>
            <div class="mr-3">
                <select class="form-select borderless border-round" wire:model="filter">
                    <option value="">All</option>
                    <option value="new">New</option>
                    <option value="in-progress">In-Progress</option>
                    <option value="on-hold">On-Hold</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
        </div>
        <div id="tk-group-actions">
            <a href="/tickets/create" class="btn btn-marine shadow">
                New Ticket
            </a>
        </div>
    </div>
    {{-- <div class="row mb-4">
        <div class="col-sm">
            <div class="search" style="max-width: 300px;">
                <input type="text" class="form-control search-input borderless" placeholder="Search..."
                    name="" id="tk-search-ticket" wire:model="search">
            </div>
        </div>
        <div class="col-auto">
            <div>
                <select class="form-select">
                    <option value="all">All</option>
                    <option value="new">New</option>
                    <option value="in-progres">In-Progress</option>
                    <option value="on-hold">On-Hold</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
        </div>
        <div class="col-auto right">
            <a href="/tickets/create" class="btn btn-marine shadow">
                New Ticket
            </a>
        </div>
    </div> --}}
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="card card-body border-round shadow-sm p-0" style="overflow-x: auto;">
                <table class="table table-borderless table-hover">
                    <thead class="bg-marine-dark fg-white">
                        <th class="pt-3">Key</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Title</th>
                        <th>Group</th>
                        <th>Assignee</th>
                        <th>Reporter</th>
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
                                    <td class="td-break" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $ticket->title }}">
                                        <span>
                                            @if (Str::length($ticket->title) > 100)
                                                {{ Str::substr($ticket->title, 0, 100) . '...' }}
                                            @else
                                                {{ $ticket->title }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        {{ $ticket->group_name }}
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
                                    <td>
                                        {{ $ticket->reporter_fn . ' ' . $ticket->reporter_ln }}
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
                <div class="p-3 pt-0 right">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
