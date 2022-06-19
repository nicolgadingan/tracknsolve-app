<div class="">
    @php
        $whereami   =   explode("/", request()->path())[0];
        $whatami    =   auth()->user()->role;
    @endphp
    <div class="center" id="sidebar-brand-box">
        <a href="/" class="link-marine brand" id="brand-area">
            @include('plugins.tnsicon')
            @include('plugins.tnstext')
            <span class="for-mobile fs-re pl-2">
                {{ Str::ucfirst($whereami) }}
            </span>
        </a>
        <a href="#menu" class="link-marine fs-xl for-mobile" id="mobile-menu">
            <i class="bi bi-list"></i>
        </a>
    </div>
    <div id="sidebar-menu">
        <div class="p-4 pt-3 pb-3">
            <h6 class="text-muted">
                GENERAL
            </h6>
            <div class="side-nav">
                <a href="/dashboard" class="btn block side-nav-item mb-3 {{ $whereami == 'dashboard' ? 'active' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span>Dashboard</span>
                        <i class="bi bi-grid-fill"></i>
                    </div>
                </a>
                <a href="/tickets" class="btn block side-nav-item mb-3 {{ $whereami == 'tickets' ? 'active' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span>Tickets</span>
                        <i class="bi bi-bookmark-star-fill"></i>
                    </div>
                </a>
                <a href="/reports" class="btn block side-nav-item {{ $whereami == 'reports' ? 'active' : '' }}" hidden>
                    <div class="d-flex justify-content-between">
                        <span>Reports</span>
                        <i class="bi bi-clipboard-data-fill"></i>
                    </div>
                </a>
            </div>
        </div>
        @if (true)
        <div class="p-4 pt-3 pb-3" id="sidebar-admin">
            <h6 class="text-muted">
                ADMIN
            </h6>
            <div class="side-nav">
                <a href="/users" class="btn block side-nav-item mb-3 {{ $whereami == 'users' ? 'active' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span>Users</span>
                        <i class="bi bi-people-fill"></i>
                    </div>
                </a>
                <a href="/groups" class="btn block side-nav-item mb-3 {{ $whereami == 'groups' ? 'active' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span>Groups</span>
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </a>
                @if ($whatami == 'admin')
                <a href="/settings" class="btn block side-nav-item {{ $whereami == 'settings' ? 'active' : '' }}">
                    <div class="d-flex justify-content-between">
                        <span>Settings</span>
                        <i class="bi bi-toggles2"></i>
                    </div>
                </a>
                @endif
            </div>
        </div>
        @endif
        <div class="for-mobile p-3">
            @php
                $user   =   auth()->user();
            @endphp
            <div class="card card-body borderless">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <b>{{ Str::ucfirst($user->first_name . ' ' . $user->last_name) }}</b><br>
                        <span class="text-muted">
                            {{ Str::ucfirst($user->role) }}
                        </span>
                    </div>
                    <div class="right">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>