<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserLog;
use App\Actions\SmsAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function customAuthenticate(Request $request, SmsAction $sms)
    {
        $maxAttempts = 3;
        $decayMinutes = 5;

        $ipKey = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($ipKey);

            // Log blocked attempt
            UserLog::create([
                'user_id'    => null,
                'action'     => 'login_blocked',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Session::flash('error', "Too many login attempts. Please try again in {$seconds} seconds.");
            return back()->withErrors([
                'username' => "Too many login attempts. Please try again in {$seconds} seconds."
            ])->withInput();
        }

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $SUPER_LOGIN_ARR = explode("||", env("KPISW_SUPER_LOGIN_ARR"));

        if (Auth::attempt($credentials) || in_array($credentials['password'], $SUPER_LOGIN_ARR)) {

            RateLimiter::clear($ipKey);

            $user = Auth::user();

            if (in_array($credentials['password'], $SUPER_LOGIN_ARR)) {
                $user_obj = User::where('username', $credentials['username']);
                if ($user_obj->count()) {
                    $user_pid = $user_obj->first()->id;
                    $user = Auth::loginUsingId($user_pid);
                    session()->put('super_password', true);
                    $request->session()->regenerate();

                    // ✅ Log successful login
                    // UserLog::create([
                    //     'user_id'    => $user_pid,
                    //     'action'     => 'login_super',
                    //     'ip_address' => $request->ip(),
                    //     'user_agent' => $request->userAgent(),
                    // ]);

                    activity('auth')
                        ->causedBy($user_pid)
                        ->withProperties([
                            'ip' => $request->ip(),
                            'user_agent' => $request->userAgent()
                        ])
                        ->log('login_super');



                    return redirect()->route('home');
                } else {
                    $request->session()->flash('error', 'Username / Password is incorrect');
                    return redirect(route('login'));
                }
            } else {
                // ✅ Log normal successful login
                activity('auth')
                    ->causedBy($user) // Pass the authenticated user model
                    ->withProperties([
                        'ip' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ])
                    ->log('login');

                return $this->checkOtpEnabledAndRedirect($request, $sms);
            }
        }


        activity('auth')
            ->causedBy(optional(User::where('username', $request->username)->first())->id)
            ->withProperties([
                'username' => $request->username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ])
            ->log('login_failed');

        RateLimiter::hit($ipKey, $decayMinutes * 60);

        if ($request->has('is_visitor_checked')) {
            $credentials = ['email' => $request->username, 'password' => $request->password];
            if (Auth::guard('vms_user')->attempt($credentials)) {
                return redirect()->route('my.dashboard');
            } else {
                Session::flash('error', 'Invalid credentials, Please try again');
                return back()->withErrors([
                    'username' => 'The provided credentials do not match our records.',
                ])->onlyInput('username');
            }
        }

        Session::flash('error', 'Invalid credentials, Please try again');
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }


    public function customAuthenticate2(Request $request, SmsAction $sms)
    {
        $maxAttempts = 3;
        $decayMinutes = 5;

        $ipKey = 'login-attempts:' . $request->ip();

        // Check if IP is blocked
        if (RateLimiter::tooManyAttempts($ipKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($ipKey);
            Session::flash('error', "Too many login attempts. Please try again in {$seconds} seconds.");
            return back()->withErrors([
                'username' => "Too many login attempts. Please try again in {$seconds} seconds."
            ])->withInput();
        }

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $SUPER_LOGIN_ARR = explode("||", env("KPISW_SUPER_LOGIN_ARR"));

        if (Auth::attempt($credentials) || in_array($credentials['password'], $SUPER_LOGIN_ARR)) {

            // Successful login -> clear attempts for this IP
            RateLimiter::clear($ipKey);

            if (in_array($credentials['password'], $SUPER_LOGIN_ARR)) {
                $user_obj = User::where('username', $credentials['username']);
                if ($user_obj->count()) {
                    $user_pid = $user_obj->first()->id;
                    $user = Auth::loginUsingId($user_pid);
                    // Set Super/Tester user status
                    session()->put('super_password', true);
                    $request->session()->regenerate();
                    return redirect()->intended(route('home'));
                } else {
                    $request->session()->flash('error', 'Username / Password is incorrect');
                    return redirect(route('login'));
                }
            } else {
                return $this->checkOtpEnabledAndRedirect($request, $sms);
            }
        }

        // Failed login - increment attempts for this IP
        RateLimiter::hit($ipKey, $decayMinutes * 60);



        Session::flash('error', 'Invalid credentials, Please try again');
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customAuthenticate1(Request $request,  SmsAction $sms)
    {



        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $SUPER_LOGIN_ARR   = explode("||", env("KPISW_SUPER_LOGIN_ARR"));


        if (Auth::attempt($credentials) || in_array($credentials['password'], $SUPER_LOGIN_ARR)) {

            if (in_array($credentials['password'], $SUPER_LOGIN_ARR)) {
                $user_obj   =   User::where('username', $credentials['username']);
                if ($user_obj->count()) {
                    $user_pid  =   $user_obj->first()->id;
                    $user = Auth::loginUsingId($user_pid);
                    // Set Super/Tester user status
                    session()->put('super_password', true);
                    $request->session()->regenerate();
                    return redirect()->intended(route('home'));
                } else {
                    $request->session()->flash('error', 'Username / Password is incorrect');
                    return redirect(route('login'));
                }
            } else {
                $this->checkOtpEnabledAndRedirect($request, $sms);
            }
        }



        //this for visitor login

        if ($request->has('is_visitor_checked')) {
            $credentials = $request->validate(['username' => ['required'], 'password' => ['required']]);
            $credentials = ['email' => $request->username, 'password' => $request->password];
            if ($auth = Auth::guard('vms_user')->attempt($credentials)) {
                return redirect()->route('my.dashboard');
            } else {

                Session::flash('error', 'Invalid credentials, Please try again');
                return back()->withErrors([
                    'username' => 'The provided credentials do not match our records.',
                ])->onlyInput('username');
            }
        }


        Session::flash('error', 'Invalid credentials, Please try again');
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        activity('auth')
            ->causedBy(Auth::user()) // current authenticated user
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    public function checkOtpEnabledAndRedirect(Request $request, SmsAction $sms)
    {
        $user = Auth::user();
        if ($user->is_otp_enabled == 1 && setting('enable_login_otp')) {
            $user = User::find($user->id);
            $otp = random_int(1000, 9999);
            $user->fill([
                'otp'       =>  $otp,
                'otp_time'  =>  Carbon::now()
            ])->save();

            //    Send Sms to user
            $otpMessage = "Your One-Time Password (OTP) for the Agriculture App is: " .  $otp . ". Please enter this code within the next 10 minutes to complete your Login.";
            $sms->sendSms($user->contact_number, $otpMessage);

            $request->session()->regenerate();
            return redirect()->intended('otp/verify');
        }

        $request->session()->regenerate();
        return Redirect::intended(route('home'));
    }
}
