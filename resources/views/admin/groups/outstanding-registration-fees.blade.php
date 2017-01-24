@extends('layouts.master')

@section('title', 'Groups: Outstanding Registration Fees')

@section('content')
    <div class="content">
        <div class="grid simple">
            <div class="grid-body dataTables_wrapper">
                <h4 class="semi-bold">Outstanding Registration Fees</h4>
                <p>Groups listed below have players who registered <strong>{{ $unpaidSince }}</strong> or more and have been notified via email several times of outstanding fees.</p>
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th class="col-md-5">Name</th>
                        <th class="col-md-1 text-center">Players</th>
                        <th class="col-md-2 text-center">Fees</th>
                        <th class="col-md-2 text-center">Most Outstanding*</th>
                        <th class="col-md-3 text-center">Owner</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($groups) > 0)
                        @foreach ($groups as $group)
                            <?php
                            $pendingPlayerCount = $group->players()->pendingRegistrationPayment(Session::season())->count();
                            $firstPlayer = $group->players()->pendingRegistrationPayment(Session::season())->orderBy('player_season.created_at', 'ASC')->first();
                            ?>
                            <tr>
                                <td>
                                    <a href="/admin/groups/{{ $group->id }}" class="semi-bold">{{ $group->name }}</a>
                                    @if($group->isInactive())
                                        <span class="text-error">Inactive</span>
                                    @endif
                                    <div>
                                        {{ $group->meetingAddress->city }}, {{ $group->meetingAddress->state }} - {{ $group->program->abbreviation }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ $pendingPlayerCount }}
                                </td>
                                <td class="text-center">
                                    ${{ number_format($pendingPlayerCount * $group->program->registration_fee, 2) }}
                                </td>
                                <td class="text-center">
                                    {{ (new \Carbon\Carbon($firstPlayer->pivot->created_at))->timezone(Auth::user()->settings->timeszone())->diffInWeeks() }} weeks ago
                                </td>
                                <td class="text-center">
                                    <a href="/admin/users/{{ $group->owner->id }}">{{ $group->owner->full_name }}</a><br/>
                                    @if(!is_null($group->owner->phone))
                                        <a href='tel:+1{{ $group->owner->phone }}'>{{ Html::formatPhone($group->owner->phone) }}</a><br/>
                                    @endif
                                    <a href="mailto:{{ $group->owner->email }}">{{ $group->owner->email }}</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
                <p class="muted">* the date of the furthest past due registration fee</p>
            </div>
        </div>
    </div>
@endsection