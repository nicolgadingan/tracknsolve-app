@extends('layouts.app')

@section('page')
    New User
@endsection

@section('content')
<div class="container">
    <div class="ts-card">
        <form method="POST" action="/users">
            @csrf
            <div class="mb-4 d-flex justify-content-between">
                @include('plugins.previous', ['path'    =>  '/users'])
                <button type="submit" class="btn btn-main btn-lg shadow">
                    Create
                </button>
            </div>
            <h6 class="fg-forest">ORGANIZATION</h6>
            @include('plugins.messages')
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select name="group" id="us-group" class="form-select">
                            @if (count($groups) > 0)
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('group') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                @endforeach
                            @else
                                <option value="0">No available group</option>
                            @endif
                        </select>
                        <label for="us-group">Group</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md">
                        <div class="form-floating">
                            <select name="role" id="us-role" class="form-select">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <label for="us-role">Role</label>
                        </div>
                    </div>
                </div>
            </div>
            <h6 class="fg-forest">USER INFORMATION</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="us-username" maxlength="20" placeholder="Username" value="{{ old('username') }}">
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="us-username">Username</label>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="us-first-name" maxlength="50" placeholder="First name" value="{{ old('first_name') }}">
                        @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="us-first-name">First Name</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" id="us-last-name" maxlength="50" placeholder="Last name" value="{{ old('last_name') }}">
                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="us-first-name">Last Name</label>
                    </div>
                </div>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="us-email" maxlength="50" placeholder="Email" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="us-email">Email</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="contact_no" id="us-contact-no" maxlength="20" placeholder="Contact No" value="{{ old('contact_no') }}">
                        <label for="us-contact-no">Contact Number</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection