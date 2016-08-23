<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Http\Requests\InvitationAcceptRequest;
use BibleBowl\Http\Requests\PaymentRequest;
use BibleBowl\Invitation;
use BibleBowl\Player;
use BibleBowl\Shop\PaymentFailed;
use BibleBowl\Shop\PaymentProcessor;
use Cart;
use DB;
use Omnipay;
use Session;

class InvitationController extends Controller
{
    // @todo notify of accept/decline via email

    public function claim($guid, $action)
    {
        $invitation = Invitation::where('guid', $guid)->firstOrFail();
        if ($invitation->status != Invitation::SENT) {
            return $this->redirectRoute()->withFlashInfo('This invitation has expired or has already been claimed');
        }

        if ($action == 'accept') {
            Session::setPendingInvitation($invitation);

            return $this->redirectRoute()->withFlashSuccess('Login to accept this invitation');
        } else {
            $invitation->update([
                'status' => Invitation::DECLINED
            ]);
            return $this->redirectRoute()->withFlashSuccess('Invitation has been declined');
        }
    }

    public function redirectRoute()
    {
        if (Auth::user() != null) {
            return redirect('dashboard');
        }

        return redirect('login');
    }
}
