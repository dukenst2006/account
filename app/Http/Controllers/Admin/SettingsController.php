<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Http\Requests\Request;
use BibleBowl\Http\Requests\SettingsUpdateRequest;
use BibleBowl\Program;
use BibleBowl\User;
use Carbon\Carbon;
use DB;
use Setting;

class SettingsController extends Controller
{

    /**
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('admin.settings')
            ->with('seasonEnd', Setting::seasonEnd())
            ->with('programs', Program::orderBy('name', 'ASC')->get());
    }

    /**
     * @param SettingsUpdateRequest     $request
     *
     * @return mixed
     */
    public function update(SettingsUpdateRequest $request)
    {
        DB::transaction(function () use ($request) {
            Setting::setSeasonEnd(Carbon::createFromTimestamp(strtotime($request->get('season_end'))));
            Setting::save();

            // update programs
            foreach ($request->get('program') as $programId => $toUpdate) {
                Program::where('id', $programId)->update($toUpdate);
            }
        });

        return redirect('/admin/settings/')->withFlashSuccess('Your changes were saved');
    }
}
