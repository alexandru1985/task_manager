<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\User;
use DB;
use App\Jobs\SendMailJob;

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
    protected $redirectTo = 'tasks';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $ip = $this->getIP();
        if($ip !== "188.25.77.9") {
            $this->sendMail();
        }
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
      public function sendMail() {
        $details['email'] = 'alexandru_panzaru@yahoo.com';
        $details['subject'] = 'TestingApp';
        $details['message'] = $this->clientDetails();
        dispatch(new SendMailJob($details));
      }

      public function clientDetails() {

        $ip = $this->getIP();
        $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=". $ip);

        $result = '';
        $result .= 'IP: '.$xml->geoplugin_request . ', ';
        $result .= 'Country: '.$xml->geoplugin_countryName . ', ';
        $result .= 'City: '.$xml->geoplugin_city. ', ';
        $result .= 'Latitude: '.$xml->geoplugin_latitude. ', ';
        $result .= 'Longitude: '.$xml->geoplugin_longitude. ', ';
        $result .= 'Timezone: '.$xml->geoplugin_timezone;
        return $result;
    }
    public function getIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
        $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
