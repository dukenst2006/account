<?php namespace BibleBowl\Http\Controllers;

use BibleBowl\Role;
use Redirect;

class LegalController extends Controller
{

    public function termsOfUse()
    {
        return view('legal/terms-of-use');
    }

    public function privacyPolicy()
    {
        return view('legal/privacy-policy');
    }
}
