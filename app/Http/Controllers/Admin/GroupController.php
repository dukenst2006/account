<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Http\Requests\AdminOnlyRequest;
use App\Season;
use App\User;
use Config;
use Input;
use Session;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::where('name', 'LIKE', '%'.Input::get('q').'%')
            ->with('owner', 'program')
            ->orderBy('name', 'ASC')
            ->paginate(25);

        return view('admin.groups.index', [
            'groups' => $groups->appends(Input::only('q')),
        ]);
    }

    public function show($groupId)
    {
        $season = Session::season();
        $group = Group::findOrFail($groupId);

        return view('admin.groups.show', [
            'group'                 => $group,
            'season'                => $season,
            'activePlayers'         => $group->players()->with('guardian')->active($season)->get(),
            'inactivePlayers'       => $group->players()->with('guardian')->inactive($season)->get(),
            'pendingPaymentPlayers' => $group->players()->with('guardian')->pendingRegistrationPayment($season)->get(),
        ]);
    }

    public function getTransferOwnership(AdminOnlyRequest $request, $groupId)
    {
        $users = [];
        foreach (User::whereStatus(User::STATUS_CONFIRMED)->orderBy('last_name', 'ASC')->orderBy('first_name', 'ASC')->get() as $user) {
            $users[$user->id] = $user->last_name.', '.$user->first_name.' ('.$user->email.')';
        }

        return view('admin.groups.transfer-ownership', [
            'group' => Group::findOrFail($groupId),
            'users' => $users,
        ]);
    }

    public function postTransferOwnership(AdminOnlyRequest $request, $groupId)
    {
        $newOwner = User::findOrFail($request->input('user_id'));
        $group = Group::findOrFail($groupId);
        $group->setOwner($newOwner);

        return redirect('/admin/groups/'.$groupId)->withFlashSuccess('Ownership has been transferred and head coaches have been notified');
    }

    public function outstandingRegistrationFees()
    {
        $outstandingAtLeast = Config::get('biblebowl.reminders.notify-office-of-outstanding-registration-payments-after');
        $relativeTime = $outstandingAtLeast.' ago';
        $season = Season::current()->first();

        return view('admin.groups.outstanding-registration-fees', [
            'unpaidSince'   => $relativeTime,
            'groups'        => Group::hasPendingRegistrationPayments($season)
                ->with([
                    'owner',
                    'program',
                    'players' => function ($q) use ($season) {
                        $q->pendingRegistrationPayment($season)
                            ->active($season)
                            ->orderBy('player_season.created_at', 'DESC')
                            ->limit(1);
                    },
                ])
                ->orderBy('name', 'ASC')
                ->get(),
        ]);
    }
}
