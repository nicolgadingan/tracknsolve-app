@extends('layouts.app')

@section('page')
    Settings
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-9">
                <div class="ts-card">
                    <div class="table-header fg-yellow">
                        <h5>Usage Overview</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm">
                            <div class="ts-card p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-30">
                                        <i class="bi bi-people-fill"></i>
                                    </span>
                                    <b class="fs-xl">
                                        {{ $stats['userUsed'] }} / {{ $configs['LIMIT#USER'] }}
                                    </b>
                                </div>
                                <div class="ts-progress">
                                    @php
                                        $usedUser   =   ( $stats['userUsed'] / $configs['LIMIT#USER'] ) * 100;
                                    @endphp
                                    <div class="ts-progress-bar bg-yellow" role="progress" aria-valuemin="0" style="width: {{ $usedUser }}%;"
                                        aria-valuemax="{{ $configs['LIMIT#USER'] }}" aria-valuenow="{{ $stats['userUsed'] }}"></div>
                                </div>
                                <strong class="pt-1">USERS</strong>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="ts-card p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-30">
                                        <i class="bi bi-briefcase-fill"></i>
                                    </span>
                                    <b class="fs-xl">
                                        {{ $stats['groupUsed'] }} / {{ $configs['LIMIT#GROUP'] }}
                                    </b>
                                </div>
                                <div class="ts-progress">
                                    @php
                                        $usedGroup  =   ( $stats['groupUsed'] / $configs['LIMIT#GROUP'] ) * 100;
                                    @endphp
                                    <div class="ts-progress-bar bg-yellow" role="progress" aria-valuemin="0" style="width: {{ $usedGroup }}%;"
                                        aria-valuemax="{{ $configs['LIMIT#GROUP'] }}" aria-valuenow="{{ $stats['groupUsed'] }}"></div>
                                </div>
                                <strong class="pt-1">GROUPS</strong>
                            </div>
                        </div>
                        <div class="col-sm">
                            <div class="ts-card p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-30">
                                        <i class="bi bi-hdd-stack-fill"></i>
                                    </span>
                                    <b class="fs-xl">
                                        {{ $stats['diskInfo']['size']  . ' ' . $stats['diskInfo']['text'] }} /
                                        {{ $configs['LIMIT#DISK'] / 1024 . ' Gb' }}
                                    </b>
                                </div>
                                <div class="ts-progress">
                                    @php
                                        $usedDisk   =   ( $stats['diskInfo']['size'] / $configs['LIMIT#DISK'] ) * 100;
                                    @endphp
                                    <div class="ts-progress-bar bg-yellow" role="progress" aria-valuemin="0" style="width: {{ $usedDisk }}%;"
                                        aria-valuemax="{{ $configs['LIMIT#DISK'] }}" aria-valuenow="{{ $stats['diskInfo']['size'] }}"></div>
                                </div>
                                <strong class="pt-1">STORAGE</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="ts-card p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <b class="fs-xl">
                                {{ Str::upper($configs['SUBS_TYPE']) }}
                            </b>
                            <br>
                            <span class="fs-sm">
                                Subscription
                            </span>
                        </div>
                        <a href="#" class="link-main" style="font-size: 30pt;">
                            <i class="bi bi-arrow-up-square-fill"></i>
                        </a>
                    </div>
                    <hr>
                    <ul class="ts-list">
                        @php
                            $icon       =   '';
                            $color      =   '';
                            $liStlyle   =   'ts-list-item d-flex justify-content-between align-items-center';

                            if ($stats['pubAccess']) {
                                $icon   =   'check';
                                $color  =   'success';
                            } else {
                                $icon   =   'exclamation';
                                $color  =   'danger';
                            }
                        @endphp
                        <li class="{{ $liStlyle }}">
                            <span>User directory access</span>
                            <span>
                                <i class="bi bi-{{ $icon }}-circle-fill fg-{{ $color }} fs-re"></i>
                            </span>
                        </li>
                        <li class="{{ $liStlyle }}">
                            <span>Download attachment</span>
                            <span>
                                <i class="bi bi-{{ $icon }}-circle-fill fg-{{ $color }} fs-re"></i>
                            </span>
                        </li>
                        @php
                            $tkSeqColor =   'danger';
                            $tkSeqIcon  =   'exclamation';
                            $tkSeqMsg   =   'Sequence is outdated. Current sequence is ' . 
                                            $stats['ticketSeq']['nowSeq'] . ' (' . $stats['ticketSeq']['maxSeq'] . ').';

                            if ($stats['ticketSeq']['action'] == 'none') {
                                $tkSeqColor =   'success';
                                $tkSeqIcon  =   'check';
                                $tkSeqMsg   =   'Sequence is updated. Current sequence is ' . $stats['ticketSeq']['nowSeq'] . '.';
                            }
                        @endphp
                        <li class="{{ $liStlyle }}" data-bs-toggle="tooltip" data-bs-placement="bottom" html="true"
                            title="{{ $tkSeqMsg }}">
                            <span>Ticket Sequence</span>
                            <span>
                                <i class="bi bi-{{ $tkSeqIcon }}-circle-fill fg-{{ $tkSeqColor }} fs-re"></i>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection