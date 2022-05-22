<div class="container-fluid">
    @php
        $uinf    =   auth()->user();
    @endphp
    <div class="d-flex justify-content-between mb-4">
        <div id="us-group-updates" class="d-flex">
            <div class="mr-3">
                <div class="search" style="max-width: 300px;">
                    <input type="text" class="form-control search-input borderless" placeholder="Search..."
                        name="user_search" id="us-search-user" wire:model="keyword">
                </div>
            </div>
            <div class="mr-3">
                <div class="has-icon has-icon-start">
                    <select class="form-select borderless border-round has-icon-form" wire:model="fltrRole">
                        <option value="">All</option>
                        @if (count($roles) > 0)
                            @foreach ($roles as $role)
                                <option value="{{ $role->role }}">{{ Str::ucfirst($role->role) }}</option>
                            @endforeach
                        @endif
                    </select>
                    <span class="has-icon-this">
                        <i class="bi bi-person-bounding-box fs-re"></i>
                    </span>
                </div>
            </div>
        </div>
        <div id="us-group-actions">
            @if ($uinf->role == 'admin')
                <a href="/users/create" class="btn btn-marine shadow">
                    New User
                </a>
            @endif
        </div>
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="card card-body border-round p-0 shadow-sm">
                <table class="table table-hover table-borderless">
                    <thead class="fg-white bg-marine-dark">
                        <th class="pt-3">Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Group</th>
                        <th class="right">Joined</th>
                        @if ($uinf->role == 'super')
                            <th></th>
                        @endif
                    </thead>
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr class="align-middle">
                                    <td>
                                        <a href="/users/{{ $user->id }}" class="link-marine us-view-trigger">
                                            <strong>
                                                {{ $user->first_name . ' ' . $user->last_name }}
                                            </strong>
                                        </a>
                                    </td>
                                    <td>
                                        {{ Str::ucfirst($user->role) }}
                                    </td>
                                    <td>
                                        @php
                                            $status =   ($user->status == 'A') ? 'Active' : 'Inactive';
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
                                        <a href="mailto:{{ str_replace('.', '@@', $user->email) }}" class="link-marine"
                                            onclick="this.href=this.href.replace('@@', '.')">
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $user->username }}
                                    </td>
                                    <td>
                                        {{ $user->group->name }}
                                    </td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($user->created_at)->diffForHumans() }}
                                    </td>
                                    @if ($uinf->role == 'super')
                                        <td class="right">
                                            <a href="#delete-{{ $user->slug }}" class="link-danger fs-lg us-del-user" data-value="{{ $user->id }}"
                                                aria-label="{{ $user->first_name . ' ' . $user->last_name }}" data-bs-toggle="tooltip" data-bs-placement="left"
                                                title="Delete user">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    No users found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="p-3 pt-0 d-flex justify-content-between">
                    <span class="fg-forest">
                        @php
                            $_tail   =   ($users->total() > 1) ? 's' : '';
                        @endphp
                        {{ 'Showing ' . $users->total() . ' user' . $_tail }}
                    </span>
                    <span>
                        {{ $users->links() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    $(document).ready(function() {
        $("body").on("click", ".us-del-user", function() {
            var userId  =   $(this).data("value"),
                userNm  =   $(this).attr("aria-label"),
                modal   =   $("#us-delt-user-modal"),
                label   =   $("#us-delt-user-label"),
                uName   =   $("#us-delt-user-name"),
                uForm   =   $("#us-delt-user-form");

            label.text("Deleting " + userNm);
            uName.text(userNm);
            uForm.attr("action", "/groups/" + userId);

            modal.modal("show");
        });
    });
</script>