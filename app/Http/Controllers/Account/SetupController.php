<?php namespace BibleBowl\Http\Controllers\Account;

use App;
use Auth;
use BibleBowl\Address;
use BibleBowl\Http\Controllers\Controller;
use BibleBowl\Support\Scrubber;
use BibleBowl\User;
use BibleBowl\RegistrationSurvey;
use BibleBowl\RegistrationSurveyAnswer;
use BibleBowl\RegistrationSurveyQuestion;
use DB;
use Illuminate\Http\Request;
use Redirect;

class SetupController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getSetup()
    {
        return view('account.setup')
            ->withQuestions(RegistrationSurveyQuestion::orderBy('order')->get());
    }

    /**
     * @return mixed
     */
    public function postSetup(Request $request, Scrubber $scrubber)
    {
        $request->merge([
            'name'    => 'Home', //default address name,
            'phone' => $scrubber->integer($request->get('phone')) //strip non-int characters
        ]);

        $userRules = array_only(User::validationRules(), ['gender', 'phone', 'first_name', 'last_name']);
        $this->validate($request, array_merge(Address::validationRules(), $userRules), Address::validationMessages());

        //update the info
        DB::transaction(function () use ($request) {
            $user = Auth::user();
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->phone = $request->get('phone');
            $user->gender = $request->get('gender');
            $user->save();

            // set timezone
            $settings = $user->settings;
            $settings->setTimezone($request->input('timezone'));
            $user->update([
                'settings' => $settings
            ]);

            // add user address
            $address = App::make(Address::class, [$request->except([
                'first_name', 'last_name', 'phone', 'gender', 'timezone', 'answer', 'other'
            ])]);
            $user->addresses()->save($address);
            $user->update(['primary_address_id' => $address->id]);

            // record survey
            if ($request->has('answer') && count($request->get('answer')) > 0) {
                $surveys = [];
                foreach ($request->get('answer') as $questionId => $answers) {
                    foreach ($answers as $answerId => $true) {
                        $surveys[] = app(RegistrationSurvey::class, [[
                            'answer_id' => $answerId
                        ]]);
                    }

                    // update that question's "Other"
                    if($request->has('other.'.$questionId) && strlen($request->get('other')[$questionId]) > 0) {
                        $otherAnswer = RegistrationSurveyAnswer::where('question_id', $questionId)->where('answer', 'Other')->first();
                        $surveys[] = app(RegistrationSurvey::class, [[
                            'answer_id'     => $otherAnswer->id,
                            'other'         => $request->get('other')[$questionId]
                        ]]);
                    }
                }
                $user->surveys()->saveMany($surveys);
            }
        });

        return redirect('/dashboard');
    }
}
