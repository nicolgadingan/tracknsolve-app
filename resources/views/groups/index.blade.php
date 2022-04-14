@extends('layouts.app')

@section('page')
    Groups
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col right">
            <a href="/groups/create" class="btn btn-marine shadow">
                New Group
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Groups</b>
                </h6>
                <table class="table table-borderless table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Manager</th>
                        <th class="right">Created</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
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