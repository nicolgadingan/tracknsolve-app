@extends('layouts.app')

@section('page')
    Dashboard
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="card card-body mb-4 border-round border-forest-light">
                    <h6>
                        <b>Unassigned Tickets</b>
                    </h6>
                    <table class="table table-borderless table-hover">
                        <thead>
                            <th>Key</th>
                            <th>Description</th>
                            <th>Reporter</th>
                            <th class="right">Created</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    No unassigned tickets.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card card-body border-round border-forest-light">
                    <h6>
                        <b>My Tickets</b>
                    </h6>
                    <table class="table table-borderless table-hover">
                        <thead>
                            <th>Key</th>
                            <th>Description</th>
                            <th>Reporter</th>
                            <th class="right">Created</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4">
                                    You don't have open tickets.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm">
                <div class="card card-body border-round border-forest-light bg-forest">
                    <h6 class="fg-white">
                        Breakdown
                    </h6>
                    <a href="#unassigned" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-secondary">
                                <i class="bi bi-clipboard-fill"></i>
                                Unassigned
                            </span>
                            <span>0</span>
                        </div>
                    </a>
                    <a href="#in-progress" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-primary">
                                <i class="bi bi-clipboard-plus-fill"></i>
                                In Progress
                            </span>
                            <span>20</span>
                        </div>
                    </a>
                    <a href="#on-hold" class="btn btn-light mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="fg-warning">
                                <i class="bi bi-clipboard-minus-fill"></i>
                                On Hold
                            </span>
                            <span>4</span>
                        </div>
                    </a>
                    <a href="#resolved" class="btn btn-light mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="fg-success">
                                <i class="bi bi-clipboard-check-fill"></i>
                                Resolved
                            </span>
                            <span>110</span>
                        </div>
                    </a>
                    <div class="mb-3 right">
                        <a href="/tickets/create" class="btn btn-marine shadow">
                            New Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
