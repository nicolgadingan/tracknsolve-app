<div class="card card-body border-round border-forest-light">
    <div class="d-flex justify-content-between mb-3 align-items-end">
        <h6>
            <b>All Users</b>
        </h6>
        <div class="search">
            <input type="text" class="form-control search-input" placeholder="Search..."
                name="user_search" id="us-search-user" wire:model="keyword">
        </div>
    </div>
    <div>
        <table class="table table-hover">
            <thead class="bg-light">
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Email</th>
                <th>Username</th>
                <th>Group</th>
                <th class="right">Joined</th>
                <th class="right"></th>
            </thead>
            <tbody>
                @if (count($users) > 0)
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                {{ $user->first_name . ' ' . $user->last_name }}
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
                            <td class="right">
                                <div class="btn-group dropstart no-content">
                                    <i class="bi bi-three-dots-vertical"></i>
                                    {{-- <a href="#options-for-{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false"
                                        class="dropdown-toggle @if ($uinf->role == 'user') ? link-secondary click-disable : link-primary @endif">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu right">
                                        <li class="dropdown-item-text">
                                            <span class="text-muted">Options</span>
                                        </li>
                                        @if ($uinf->role == 'admin')
                                        <li class="dropdown-item us-delete" data-id="{{ $user->id }}">
                                            <a href="#delete-{{ $user->id }}" class="link-danger">
                                                Delete
                                            </a>
                                        </li>
                                        @endif
                                    </ul> --}}
                                </div>
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
    </div>
    <div class="pagination-right">
        @if (count($users) > 0)
            {{ $users->links() }}
        @endif
    </div>
    <form id="us-delete-form" action="" method="POST">
        @method("DELETE")
        @csrf
    </form>
</div>