<?php

namespace App\Http\Controllers\Tournaments\Admin;

use App\Competition\Tournaments\InviteCoordinator;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tournament\CoordinatorInviteRequest;
use App\Http\Requests\Tournament\RemoveCoordinatorRequest;
use App\Http\Requests\Tournament\RetractCoordinatorInviteRequest;
use App\Invitation;
use App\Tournament;
use App\User;
use Auth;
use DB;
use Mail;

class CoordinatorsController extends Controller
{
    public function listCoordinators(Tournament $tournament)
    {
        return view('tournaments.admin.coordinators.users')
            ->withTournament($tournament)
            ->with('pendingInvitations', $tournament->invitations()->with('user')->where('status', Invitation::SENT)->get());
    }

    public function getCoordinatorInvite(Tournament $tournament)
    {
        return view('tournaments.admin.coordinators.user-invite')
            ->withTournament($tournament);
    }

    public function sendCoordinatorInvite(CoordinatorInviteRequest $request, $id)
    {
        $tournament = Tournament::findOrFail($request->route('tournament'));
        $user = User::where('email', $request->get('email'))->first();

        DB::beginTransaction();

        $recipientName = null;
        if (is_null($user)) {
            $recipientEmail = $request->get('email');
        } else {
            $recipientEmail = $user->email;
            $recipientName = $user->full_name;
        }

        $invitation = Invitation::create([
            'type'          => Invitation::TYPE_MANAGE_TOURNAMENTS,
            'email'         => is_null($user) ? $request->get('email') : null,
            'user_id'       => is_null($user) ? null : $user->id,
            'inviter_id'    => Auth::user()->id,
            'tournament_id' => $tournament->id,
        ]);

        Mail::to($recipientEmail, $recipientName)->queue(new InviteCoordinator($invitation));

        DB::commit();

        return redirect('/admin/tournaments/'.$tournament->id.'/coordinators')
            ->withFlashSuccess('Invitation has been sent');
    }

    public function removeCoordinator(RemoveCoordinatorRequest $request, Tournament $tournament, $userId)
    {
        DB::beginTransaction();
        $tournament->removeCoordinator(User::findOrFail($userId));
        DB::commit();

        return redirect()->back()->withFlashSuccess('Coordinator has been removed');
    }

    public function retractInvite(RetractCoordinatorInviteRequest $request, Tournament $tournament, $invitationId)
    {
        Invitation::where('id', $invitationId)->delete();

        return redirect('/admin/tournaments/'.$tournament->id.'/coordinators')
            ->withFlashSuccess('Invitation has been retracted');
    }
}
