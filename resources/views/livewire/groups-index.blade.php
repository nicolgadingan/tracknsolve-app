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
            @if (auth()->user()->role == "admin")
                <button class="btn btn-marine" data-bs-toggle="modal" data-bs-target="#gr-new-group-form">New Group</button>
            @endif
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
                    </thead>
                    <tbody>
                        {{ $data }}
                        @if (count($data) > 0)
                            @foreach ($data as $group)
                                <tr>
                                    <td>
                                        <a href="#view-{{ $group->slug }}" class="link-marine gr-view-group" data-value="{{ $group->id }}">
                                            <b>{{ $group->name }}</b>
                                        </a>
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
    <div class="p-3 mb-3" hidden>
        {{ 'Searching: ' . $searchgroup }}
        <br>
        {{ 'Count: ' . count($data) }}
        <br>
        {{ 'Groups: ' . $data }}
    </div>
</div>
