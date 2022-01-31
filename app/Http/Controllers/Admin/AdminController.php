<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use App\Models\Admin;
use App\Models\User;
use App\Events\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public $guard;

    public function __construct()
    {
        $this->guard = "admin";

        $this->middleware('auth:' . $this->guard , ['except' => ['login', 'loginForm' , 'register' , 'create' ]]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $admins = Admin::all();

        $users = User::all();

        return view('admin.home', [
            'admins' => $admins,
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('admin.auth.register');
    }

    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function register(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();

        $this->store($data);

        $this->login($request);         
    }


    public function store(array $data)
    {
      return Admin::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }   
    
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            return \Redirect::route('admins.index');
        }
  
        return redirect("login")->withSuccess('Login details are not valid');
    }

    public function logout() 
    {
        Session::flush();

        Auth::guard('admin')->logout();
  
        return Auth::guard('admin')->user();
    }
}
