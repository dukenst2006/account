<?php namespace BibleBowl\Http\Controllers\Admin;

use BibleBowl\Role;
use BibleBowl\User;
use Illuminate\Http\Request;
use Input;
use Session;
use DB;

class UserController extends Controller
{

    public function index()
    {
        $users = User::where('first_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhere('last_name', 'LIKE', '%'.Input::get('q').'%')
            ->orWhere('email', 'LIKE', '%'.Input::get('q').'%')
            ->with('primaryAddress')
            ->orderBy('last_name', 'ASC')
            ->orderBy('first_name', 'ASC')
            ->paginate(25);

        return view('/admin/users/index', [
            'users' => $users->appends(Input::only('q'))
        ]);
    }

    public function show($userId)
    {
        return view('/admin/users/show', [
            'user' => User::findOrFail($userId)
        ]);
    }

    public function roles($userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        return view('admin.users.roles', [
            'user' => $user,
            'roles' => Role::orderBy('name', 'ASC')->get()
        ]);
    }

    public function updateRoles(Request $request, $userId)
    {
        /** @var User $user */
        $user = User::with('roles')->findOrFail($userId);

        DB::transaction(function () use ($request, $user) {
            $roleIds = $request->get('role', []);
            foreach (Role::editable()->get() as $role) {
                $hasRole = $user->is($role->name);
                $shouldHaveRole = array_key_exists($role->id, $roleIds);
                if ($hasRole && $shouldHaveRole === false) {
                    $user->retract($role);
                } elseif ($hasRole === false && $shouldHaveRole) {
                    $user->assign($role);
                }
            }
        });

        return redirect('/admin/users/'.$userId)->withFlashSuccess('Your changes were saved');
    }

    /**
     * Allow the admin to switch users
     *
     * @param $userId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function switchUser($userId)
    {
        $user = User::findOrFail($userId);

        Session::switchUser($user);

        return redirect('dashboard')->withFlashSuccess("You're now logged in as ".$user->full_name.", log out to switch back");
    }
}
