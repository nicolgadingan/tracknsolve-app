@extends('layouts.app')

@section('page')
    Groups
@endsection

@section('content')
<div class="container-fluid">
    @php
        $user   =   auth()->user();
    @endphp
    @include('plugins.messages')
    @if ($user->role == 'admin')
    <div class="row mb-4">
        <div class="col">
            <div class="card card-body border-round border-forest-light pt-4">
                <h6 class="fg-forest">NEW GROUP INFORMATION</h6>
                <form action="/groups" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" name="name" class="form-control" id="gr-name" placeholder="Group Name" maxlength="50" required> 
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="gr-name">Group Name</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select name="manager" id="gr-manager" class="form-select">
                                    @if (count($managers) > 0)
                                        @foreach ($managers as $mgr)
                                            <option value="{{ $mgr->id }}" {{ ($mgr->id == $user->id) ? 'selected' : '' }}>
                                                {{ ucwords($mgr->first_name) . ' ' . ucwords($mgr->last_name) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="{{ $user->id }}">{{ ucwords($user->first_name . ' ' . $user->last_name) }}</option>
                                    @endif
                                </select>
                                <label for="gr-manager">Manager</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col right">
                            <button type="submit" class="btn btn-marine">Save Group</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Groups</b>
                </h6>
                <table class="table table-hover">
                    <thead class="bg-light">
                        <th>Name</th>
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
                        @if (count($groups) > 0)
                            @foreach ($groups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
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
                                    <td>{{ $group->first_name . ' ' . $group->last_name }}</td>
                                    <td class="right">
                                        {{ \Carbon\Carbon::create($group->created_at)->diffForHumans() }}
                                    </td>
                                    <td class="right">
                                        <a href="#options-for-{{ $group->slug }}" class="link-primary">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </a>
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
                @if (count($groups) > 0)
                    {{ $groups->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection