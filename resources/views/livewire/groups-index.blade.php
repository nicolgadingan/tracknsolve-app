<div>
    @php
        $uinf   =   auth()->user();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="search" style="max-width: 300px;">
                <input type="text" class="form-control search-input borderless" placeholder="Search..."
                    name="" wire:model="searchgroup">
            </div>
        </div>
        <div class="col-md right">
            <button class="btn btn-marine" data-bs-toggle="modal" data-bs-target="#gr-new-group-form">New Group</button>
        </div>
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="card card-body border-round borderless shadow-sm p-0">
                <table class="table table-borderless table-hover">
                    <thead class="bg-marine-dark fg-white">
                        <th class="pt-3">Name</th>
                        <th>Status</th>
                        <th>Manager</th>
                        <th class="right">Created</th>
                        <th class="right">
                            {{-- <span class="text-muted fs-lg">
                                <i class="bi bi-three-dots"></i>
                            </span> --}}
                        </th>
                    </thead>
                    <tbody>
                        {{ $data }}
                        @if (count($data) > 0)
                            @foreach ($data as $group)
                                <tr>
                                    <td>
                                        {{ $group->name }}
                                    </td>
                                    <td>
                                        @php
                                            $status =   ($group->status == 'A') ? 'Active' : 'Inactive';
                                            switch ($status) {
                                                case 'Active':
                                                    $theme  =   'success';
                                                    break;
                                                default:
                                                    $theme  =   'secondary';
                                                    break;
                                            }
                                        @endphp
                                        <span class="dot dot-{{ $theme }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $group->first_name . ' ' . $group->last_name }}
                                    </td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($group->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="right">
                                        <div class="btn-group dropstart no-content">
                                            <a data-bs-toggle="dropdown" aria-expanded="false"
                                                class="dropdown-toggle @if ($uinf->role == 'user') ? link-secondary click-disable : click-enable link-primary @endif">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu right">
                                                <li class="dropdown-item-text">
                                                    <small class="text-muted">Options</small>
                                                </li>
                                                @if ($uinf->role == 'admin')
                                                <li class="dropdown-item">
                                                    <a href="#" class="link-danger">Delete</a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    No Groups found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>
    <div class="p-3 mb-3">
        {{ 'Searching: ' . $searchgroup }}
        <br>
        {{ 'Count: ' . count($data) }}
        <br>
        {{ 'Groups: ' . $data }}
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="gr-new-group-form" tabindex="-1" aria-labelledby="gr-new-group-label" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gr-new-group-label">New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
