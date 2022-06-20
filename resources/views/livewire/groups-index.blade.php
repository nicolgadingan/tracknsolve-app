<div>
    @php
        $uinf   =   auth()->user();
    @endphp

    <div class="row g-3 mb-4 dark-theme">
        <div class="col-md">
            <div class="search" style="max-width: 300px;">
                <input type="text" class="form-control search-input borderless" placeholder="Search..."
                    name="" wire:model="searchgroup">
            </div>
        </div>
        <div class="col-md right">
            @if (auth()->user()->role == "admin")
                <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#gr-new-group-form">New Group</button>
            @endif
        </div>
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="ts-card-dark">
                <div class="table-header fg-yellow">
                    <h5>
                        <span>All Groups</span>
                    </h5>
                </div>
                <table class="table table-borderless ts-table">
                    <thead class="fg-white">
                        <th class="pt-3">Name</th>
                        <th>Status</th>
                        <th>Manager</th>
                        <th>Members</th>
                        <th class="right">Created</th>
                        @if ($uinf->role == 'admin')
                            <th></th>
                        @endif
                    </thead>
                    <tbody>
                        {{ $data }}
                        @if (count($data) > 0)
                            @foreach ($data as $group)
                                <tr class="align-middle">
                                    <td>
                                        <a href="/groups/{{ $group->id }}" class="link-yellow">
                                            {{ $group->name }}
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $status =   '';
                                            $theme  =   '';

                                            switch ($group->status) {
                                                case 'A':
                                                    $status =   'Active';
                                                    $theme  =   'success';
                                                    break;
                                                case 'I':
                                                    $status =   'Inactive';
                                                    $theme  =   'secondary';
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
                                        <a href="/users/{{ $group->manager->id }}" class="link-light">
                                            {{ $group->manager->first_name . ' ' . $group->manager->last_name }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ count($group->members) }}
                                    </td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($group->created_at)->diffForHumans() }}
                                    </td>
                                    @if ($uinf->role == 'admin')
                                        <td class="right">
                                            @if ($status == 'Active')
                                                <a href="#deac-{{ $group->slug }}" class="link-success gr-deactivate mr-1" data-value="{{ $group->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="left" title="Group is Active.<br>Click to deactivate."
                                                    data-bs-html="true">
                                                    <i class="bi bi-toggle-on fs-lg"></i>
                                                </a>
                                            @else
                                                <a href="#acti-{{ $group->slug }}" class="link-secondary gr-activate mr-1" data-value="{{ $group->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="left" title="Group is Inactive.<br> Click to activate."
                                                    data-bs-html="true">
                                                    <i class="bi bi-toggle-off fs-lg"></i>
                                                </a>
                                            @endif
                                            @if ($configs['canDelete'] == 'Y')
                                                <a href="#delete-{{ $group->slug }}" class="link-danger gr-delete" data-value="{{ $group->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="left" title="Delete group" aria-label="{{ $group->name }}">
                                                    <i class="bi bi-trash-fill fs-lg"></i>
                                                </a>
                                            @endif
                                        </td>
                                    @endif
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
                <div class="p-3 pt-0 d-flex justify-content-between">
                    <span class="fg-forest">
                        {{ 'Showing ' . $data->total() . ' groups' }}
                    </span>
                    <span>
                        {{ $data->links() }}
                    </span>
                </div>
            </div>
        </div>
        <form action="" method="POST" id="gr-deac-form" class="d-none">
            @csrf
            @method('PUT')
        </form>
        <form action="" method="post" id="gr-acti-form" class="d-none">
            @csrf
            @method('PUT')
        </form>
    </div>
    <div class="p-3 mb-3" hidden>
        {{ 'Searching: ' . $searchgroup }}
        <br>
        {{ 'Count: ' . count($data) }}
        <br>
        {{ 'Groups: ' . $data }}
    </div>
</div>

<script>
    $(document).ready(function() {

        // Deactivate group
        $("body").on("click", ".gr-deactivate", function() {
            var form    =   $("#gr-deac-form"),
                grpId   =   $(this).data("value");

            event.preventDefault();

            form.attr("action", "/groups/" + grpId + "/deactivate");
            form.submit();

        });

        // Activate group
        $("body").on("click", ".gr-activate", function() {
            var form    =   $("#gr-acti-form"),
                grpId   =   $(this).data("value");

            event.preventDefault();

            form.attr("action", "/groups/" + grpId + "/activate");
            form.submit();

        });

        // Delete group
        $("body").on("click", ".gr-delete", function() {
            var modal   =   $("#gr-delt-group-modal"),
                title   =   $("#gr-delt-group-modal #gr-delt-group-label"),
                nameEl  =   $("#gr-delt-group-modal #gr-delt-name"),
                grpId   =   $(this).data("value"),
                grpName =   $(this).attr("aria-label"),
                grpForm =   $("#gr-delt-group-form");

            event.preventDefault();

            grpForm.attr("action", "/groups/" + grpId);
            title.text("Deleting " + grpName);
            nameEl.text(grpName);

            modal.modal("show");

        });
    });
</script>