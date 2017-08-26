<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegistrationCompleted;
use App\Exceptions\NoActiveAccountException;
use App\Http\AuthTraits\Social\ManagesSocialAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Auth\Events\Registered;
use App\Models\Associate;
use Illuminate\Support\Facades\Validator;
class AuthController extends RegisterController
{
    use AuthenticatesUsers, ManagesSocialAuth;

    protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout',
            'redirectToProvider',
            'handleProviderCallback']
        ]);
    }

    private function checkStatusLevel()
    {
        if ( ! Auth::user()->isActiveStatus()) {
            Auth::logout();
            throw new NoActiveAccountException;
        }
    }

    public function redirectPath()
    {
        if (Auth::user()->isAdmin()){
            return 'admin';
        }
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        $this->validateLogin($request);


        //Check if the user is
        //$mResult=0 User with Valid Encrypted Password
        //$mResult=1 User with Invalid Encrypted Password
        //$mResult=2 Invalid Username & Password
        $mResult = $this->checkUserHasValidEncryptedPassword($request);
        if($mResult==1){
            return redirect()->route('existing-user-update')->with('current_username',  $request->input($this->username()));
        }




        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }



        if ($this->attemptLogin($request)) {

            $this->checkStatusLevel();

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        event(new RegistrationCompleted($user));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }





    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        //return 'username';
        return 'User_Name_';
    }


    public function checkUserHasValidEncryptedPassword(Request $request)
    {

        $username = $request->input($this->username());
        $password = $request->input('password');
        $users = Associate::where($this->username(), $username)->where('Status', '!=', 'Removed')->first();

        //User found
        if ($users) {

            if($users->Password==$password && $users->password_encrypted==''){
                return 1;
            }else{
                return 0;
            }
        }

        return 2;


    }

    public function showExistingRegistrationForm(){
        return view('auth.existing-user');
    }

    public function updateExisting(Request $request){
        $validator = $this->validator($request->all())->validate();

        $username = $request->input($this->username());

        $old_password = $request->input('password_current');
        $new_password = $request->input('password');
        $user = Associate::
                    where($this->username(), $username)
                    ->where('Password', $old_password)
                    ->where('Status', '!=', 'Removed')
                    ->first();


        if($user) {
            $user->password_encrypted = bcrypt($new_password);
            $user->email = $user->Email_Address; //TO REMOVE
            $user->status_id = 10;
            $user->name = $user->Forename . " " . $user->Initials . " " . $user->Surname; //TO REMOVE
            $user->save();
            
            return redirect()->route('login');
        }else{
           return $this->sendFailedUpdateRegistrationResponse($request);
        }
    }

    protected function validator(array $data)
    {

        return Validator::make($data, [
            'User_Name_' => 'required|max:255|exists:associate',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    protected function sendFailedUpdateRegistrationResponse(Request $request)
    {
        $errors = ['password_current' => trans('auth.failed')];

        if ($request->expectsJson()) {
            return response()->json($errors, 422);
        }

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors($errors);
    }
}
