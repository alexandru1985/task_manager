<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;
use DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/api/tasks';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->cleanData();
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $create['name'] = $user->getName();
            $create['email'] = $user->getEmail();
            $create['facebook_id'] = $user->getId();
    
            $createUserId = User::create($create);
            Auth::loginUsingId($createUserId->id);
    
            return redirect('tasks');
    
        } catch (Exception $e) {
            return redirect('redirect');
        }
      }
    public function cleanData() {
        DB::table('users')->where('id', '>', 1)->delete();
        DB::update('ALTER TABLE users AUTO_INCREMENT = 2');
        DB::table('clients')->truncate();
        DB::table('projects')->truncate();
        DB::table('roles')->truncate();
        DB::table('tasks')->truncate();
        DB::table('user_tasks')->truncate();
    }
    
}
