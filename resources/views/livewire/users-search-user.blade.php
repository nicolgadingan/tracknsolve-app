<div class="container-fluid">
    @php
        $uinf    =   auth()->user();
    @endphp
    <div class="row mb-4"> 
        <div class="col-sm">
            <div class="search" style="max-width: 300px;">
                <input type="text" class="form-control search-input borderless" placeholder="Search..."
                    name="user_search" id="us-search-user" wire:model="keyword">
            </div>
        </div>
        <div class="col-sm right">
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
                    </thead>
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
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
                                        {{ $user->email }}
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
                <div class="p-3 pt-0 d-flex justify-content-end">
                    {{ $users->links() }}
                </div>
                <form id="us-delete-form" action="" method="POST">
                    @method("DELETE")
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    var popover = new bootstrap.Popover(document.querySelector('.popover-dismiss'), {
        trigger: 'focus'
    });
</script>