<?php
   
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin\User as controllerModel;

use Log;
  
class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('changePassword');
    } 

    public function admin($user_id)
    {
        return view('changePasswordAdmin', compact('user_id'));
    } 

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'new_confirm_password' => ['same:new_password'],
        ],[
            'new_password.regex' => '1 uppercase, 1 lowercase & 1 number required',
        ]);
   
        controllerModel::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Password change successfully.');   
    }

    public function adminStore(Request $request)
    {
        $user_id = $request->user_id;
        $result = controllerModel::findOrFail($user_id);   

        $request->validate([
            'new_password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'new_confirm_password' => ['same:new_password'],
        ],[
            'new_password.regex' => '1 uppercase, 1 lowercase & 1 number required',
        ]);
   
        $result->update(['password'=> Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Password change successfully.');   
    }
}