<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Session;
use Validator;
use Illuminate\Support\Facades\Http;
// use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function index()
    {
        if (Session::get('login') == 'dosen' || Session::get('login') == 'admin') {
            return redirect('/home');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $response = Http::asForm()->post(config('app.urlApi') . 'dosen/login', [
            'username' => $request->username,
            'password' => $request->password,
            'APIKEY' => config('app.APIKEY')
        ]);

        $resdsn = $response->json();
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }
        $res = User::where(['username' => $request->username, 'password' => $request->password])->first();
        // dd($res);
        if ($resdsn['success']) {
            Session::put('data', $resdsn['user']);
            Session::put('login', 'dosen');
            return redirect()->intended('/dosen/home')->with('login-success', $resdsn["user"]["nmdosMSDOS"] . ' ' . $resdsn["user"]["gelarMSDOS"]);
        } else if (isset($res)) {
            Session::put('data', $res);
            Session::put('login', 'admin');
            Session::put('admin', $res);
            // dd($res);
            return redirect()->intended('/admin/home')->with('login-success', $res->nama);
        } else {
            return back()->with('login-failed', 'Username / Password Incorrect!');
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login')->with('logout-success', 'Kamu berhasil Keluar');
    }
}
