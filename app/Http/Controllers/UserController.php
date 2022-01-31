<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public $guard;

    public function __construct()
    {
        $this->guard = "user";

        $this->middleware('auth:' . $this->guard , ['except' => ['login', 'loginForm' , 'register' , 'create' ]]);
    }

    public function index()
    {   
        $admins = Admin::all();

        $users = User::all();

        return view('user.home', [
            'admins' => $admins,
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('user.auth.register');
    }

    public function register(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();

        $this->store($data);

        $this->login($request);         
    }

    public function store(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }   

    public function loginForm()
    {
        return view('user.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials)) {

            return \Redirect::route('users.index');
        }
  
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function logout() 
    {
        Session::flush();

        Auth::guard('user')->logout();
  
        return redirect()->route('home');
    }
}
