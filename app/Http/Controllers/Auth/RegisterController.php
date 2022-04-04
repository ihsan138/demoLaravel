<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Admin\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Storage;
use Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'min:5', 'unique:users', 'alpha_num'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'telephone' => ['required', 'string', 'min:8'],
            'role' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Select Role from SPATIE based on user input
        $role = Role::where('name', $data['role'])->first();

        $now = Carbon::now()->format('Y-m-d H:i:s');

        //create a user
        $user =  User::create([
            'status' => 'New user',
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'telephone' => $data['telephone'],
            'last_seen_at' => $now,
        ]);

        //assign the user role
        $user->assignRole($role);

        //create a folder for user in the storage
        //$response = Storage::disk('public')->makeDirectory('users/'.$user->username);
        $folder = 'users/'.$user->username.'/';                               //Set folder path and create if not exist
        if(!Storage::disk('public')->exists($folder)){                        //Every folders or filename has no inital '/'
            Storage::disk('public')->makeDirectory($folder);                  //Every folders or filename has '/' ending
            Storage::copy('common/new_user/public storage/README.txt', 'public/users/'.$user->username.'/README.txt');
        }

        //$response = Storage::disk('private')->makeDirectory('users/'.$user->username);
        //Storage::copy('common/new_user/private storage/README.txt', 'users/'.$user->username.'/README.txt');

        if(!Storage::disk('private')->exists($folder)){                        //Every folders or filename has no inital '/'
            Storage::disk('private')->makeDirectory($folder);                  //Every folders or filename has '/' ending
            Storage::copy('common/new_user/private storage/README.txt', 'users/'.$user->username.'/README.txt');
        }

        return $user;
    }
}
