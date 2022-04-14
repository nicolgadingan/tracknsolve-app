@extends('layouts.app')

@section('page')
    Tickets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col right">
            <a href="/tickets/create" class="btn btn-marine shadow">
                New Ticket
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card card-body border-round border-forest-light">
                <h6>
                    <b>All Tickets</b>
                </h6>
                <table class="table table-borderless table-hover">
                    <thead>
                        <th>Key</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Reporter</th>
                        <th>Assignee</th>
                        <th class="right">Created</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">
                                No tickets.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection