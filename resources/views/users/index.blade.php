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
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Users</b>
                </h6>
                <table class="table table-borderless table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Reporter</th>
                        <th class="right">Joined</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7">
                                No Users.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection