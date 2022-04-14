@extends('layouts.app')

@section('page')
    Users
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col right">
            <a href="/users/create" class="btn btn-marine shadow">
                New User
            </a>
        </div>
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Users</b>
                </h6>
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
                                        {{ $user->name }}
                                    </td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($user->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="right">
                                        <a href="#options-for-{{ $user->slug }}" class="link-primary">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    No Users.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div>
                    @if (count($users) > 0)
                        {{ $users->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection