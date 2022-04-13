<div class="">
    <div class="pt-4 center">
        <img src="{{ asset('imgs/brand.svg') }}" alt="Yorik logo" id="sidebar-logo">
    </div>
    @php
        // Request::is('dashboard');
        $whereami   =   request()->path();
    @endphp
    <div class="p-4">
        <h6 class="fg-forest">
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
            <a href="/reports" class="btn block side-nav-item {{ $whereami == 'reports' ? 'active' : '' }}">
                <div class="d-flex justify-content-between">
                    <span>Reports</span>
                    <i class="bi bi-archive-fill"></i>
                </div>
            </a>
        </div>
    </div>
    <div class="p-4">
        <h6 class="fg-forest">
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
            <a href="/settings" class="btn block side-nav-item {{ $whereami == 'settings' ? 'active' : '' }}">
                <div class="d-flex justify-content-between">
                    <span>Settings</span>
                    <i class="bi bi-toggles2"></i>
                </div>
            </a>
        </div>
    </div>
</div>