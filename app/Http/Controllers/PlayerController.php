<?php namespace BibleBowl\Http\Controllers;

use Auth;
use BibleBowl\Http\Requests\GuardianOnlyRequest;
use BibleBowl\Player;
use BibleBowl\Players\PlayerCreator;
use Illuminate\Http\Request;
use Session;

class PlayerController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('player.create');
    }

    /**
     * @return mixed
     */
    public function store(Request $request, PlayerCreator $playerCreator)
    {
        $this->validate($request, Player::validationRules());

        $player = $playerCreator->create(Auth::user(), $request->all());

        return redirect('/dashboard')->withFlashSuccess($player->full_name.' has been added');
    }

    /**
     * @param GuardianOnlyRequest $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(GuardianOnlyRequest $request, $id)
    {
        $player = Player::findOrFail($id);
        $groupRegisteredWith = $player->groupRegisteredWith(Session::season());

        return view('player.edit')
            ->withPlayer($player)
            ->with('isRegistered', $groupRegisteredWith !== null)
            ->withRegistration($groupRegisteredWith);
    }

    /**
     * @param GuardianOnlyRequest 	$request
     * @param                     	$id
     *
     * @return mixed
     */
    public function update(GuardianOnlyRequest $request, $id)
    {
        $rules = Player::validationRules();
        $player = Player::findOrFail($id);
        $isRegistered = $player->isRegisteredWithGroup(Session::season());
        if ($isRegistered) {
            $rules['shirt_size']    = 'required';
            $rules['grade']         = 'required';
        }

        $this->validate($request, $rules);

        $player->update($request->except('shirt_size', 'grade'));
        if ($isRegistered) {
            $player->seasons()->updateExistingPivot(
                Session::season()->id,
                $request->only(['shirt_size', 'grade'])
            );
        }

        return redirect('/dashboard')->withFlashSuccess('Your changes were saved');
    }
}
