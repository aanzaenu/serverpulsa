<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function redirectTo()
    {
        if(Auth::user()->hasAnyRoles(['Admin', 'CS']))
        {
            $this->redirectTo = route('admin.home');
        }else{
            $this->redirectTo = route('main');
        }
        return $this->redirectTo;
    }
    public function login(Request $request)
    {  
        $input = $request->all();
        $this->validate($request, [
                'username' => 'required',
                'password' => 'required',
            ],
            [
                'username.required' => 'Email / Username harus diisi',
                'password.required' => 'Password harus diisi',
            ]
        );
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(Auth::attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            if(Auth::user()->hasAnyRoles(['Admin', 'CS']))
            {
                return redirect()->route('admin.home');
            }
            return redirect()->route('main');
        }else{
            return redirect()->route('login')->with('error','Login Wrong');
        }
    }
}
