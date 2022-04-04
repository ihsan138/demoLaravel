<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Admin\User as controllerModel;
use Carbon\Carbon;
use Exception;
use Storage;
use Auth;
use Log;
use DB;

use Response;
use View;
use Image;
use Str;
use File;


class ProfileController extends Controller
{
    /****************************************************************************************************************************************************************/
    /* DEFAULT METHOD SECTION - VERSION 4                                                                                                                           */
    /****************************************************************************************************************************************************************/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = controllerModel::where('username','!=','Super Admin')->paginate(6);                                //laravel pagination method
        return view('profiles.index',compact('result'));            // Browse : will be using DataTables function, refer to function getDatatable($type)

        //Alert::alert('Title', 'Message', 'Type'); // use this to display alert
        // return view('template.basic.index');                    // Browse : will be using DataTables function, refer to function getDatatable($type)
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
        // not needed, managed via users.create
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
        // not needed, managed via users.store
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //For soft delete
        $result = controllerModel::withTrashed()
        ->with('supervisor1')
        ->with('supervisor2')
        ->findOrFail($id);                          //For model with softdelete, use withTrashed() helper
        //For hard delete
        //$result = controllerModel::findOrFail($id);

        $selected_roles = $result->getRoleNames()->toArray();                                 //search roles associated with this user
        
        // Log::info($result);
        return view('profiles.show', compact('result'))
        ->with('selected_roles', $selected_roles);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $result = controllerModel::findOrFail($id);                                                      // will automatically redirect to page error:404 if not found

        // return view('template.basic.edit', compact('result'));  //to populate select multiple

        $result = Auth::user();                                           //can update own profile only, hence why using Auth instead of User

        /*For permissions, we specifically use SPATIE package */
        return view('profiles.edit', compact('result'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * 1. Update method takes Model $model as parameter.
         * 2. User A is editing a form.
         * 3. Record is deleted (hard or soft) from backend (forcibly, or by another user)
         * 4. User A click update.
         * 5. Cannot log any error as Log::info($role) does not work. Form will stuck.
         */
        /* Enable only via ajax */
        if(request()->ajax() || $request->is('api/*')){
            
            //Cast data from api
            if($request->is('api/*')){
                //Cast type first
                $request = $request['formValue'];       //get the array data from mobile
                $request = new Request($request);       //convert to type Request so that we can use with store method
            }

            /* Avoid Empty 500 Server Error with Try-Catch */
            try{
                //Get model data
                $result = controllerModel::findOrFail($id); //if fail, will return 404 error

                //Validator
                $rules2 = controllerModel::$rules2; //get rules
                //For unique record
                if ($id !== null) {               //check if $id is not empty
                    $rules2['username'] .= ",$id";     //if value of field same, then ignore (only tested for 1 column only)
                }
                $validator  = Validator::make(
                    $request->all(),
                    $rules2,
                    controllerModel::$rulesMessages
                );

                //Check validator
                if ($validator->fails())
                {
                    $errors =  $validator->messages();
                    $notification = array(
                        'alert_type' => 'warning',                                          /* "success", "error", "warning", "info" or "question" */
                        'message' => 'Woops. There seem to be an error with your input.',      /* Your custom message here                            */
                    );
                }else{
                    //Check for DB transaction. Rollback will be automatic if any previous transaction has failed
                    $query = DB::transaction(function()  use ($request, $result)
                    {
                        //Update the data
                        $data = $request->all();
                        
                        /* Custom data entry starts */
                        // For field that require reformat to comply with database , override $data array here
                        //$data['column_name'] =  value;
                        //Update the data
                        if ($request->has('avatar')) {
                            $image_input = $request->file('avatar');                              //Get image file
                            $image = Image::make($image_input->getRealPath())->resize(300,300);   //Resize it using Image Intervention, this will not work with uploadOne - UploadTrait

                            $folder = 'users/'.Auth::user()->username.'/';                        //Set folder path and create if not exist
                            if(!Storage::disk('public')->exists($folder)){                        //Every folders or filename has no inital '/'
                                Storage::disk('public')->makeDirectory($folder);                  //Every folders or filename has '/' ending
                            }

                            //$ext = $image->getClientOriginalExtension();                        //Get original file extension
                            $ext = 'jpg';                                                         //Cast mimes type to .jpg
                            //$filename = Str::slug($request->input('name')).'_'.time().'.'.$ext; //Make image name based on user name and current timestamp (unique & 'encrypted')
                            $filename = 'avatar_'.time().'.'.$ext;                                //Make image name avatar_current timestamp (unique & 'encrypted')

                            //$path = $image->storeAs($folder, $filename);                        //storeAs cannot be used with Image Intervention
                            $image->save(storage_path('app/public/'.$folder).$filename, 80);      //save() by default will refer to folder in Laratest/public
                                                                                                //need to override folder location via storage_path
                            //$this->uploadOne($image, $folder, 'public', $name);                 //This will not work with Intervention/Image, because uploadOne expects parameter 1 to be an instance of Illuminate\Http\UploadedFile

                            if(Auth::user()->avatar!='users/avatar.png'){                         //Do not delete standard avatar.
                                Storage::disk('public')->delete(Auth::user()->avatar);            //Delete previous avatar, place before $profile->avatar!!!
                            }
                            $data['avatar'] = $folder.$filename;
                        }
                        /* Custom data entry ends */

                        //Save via mass assignments, columns that are defined in fillable property in model
                        $saved = $result->update($data);

                        //Save explicitly, column that are not defined in fillable property in model
                        $result->updated_by = Auth::user()->name;
                        $saved = $result->save();
                        
                        if( $saved )
                        {
                            return true;
                        }else{
                            throw new \Exception('Unable to update data');
                        }
                    });    

                    // Check whether query successful or not
                    if($query){  //success, send a notification
                        $notification = array(
                            'alert_type' => 'success',                                      /* "success", "error", "warning", "info" or "question" */
                            'message' => 'Updated!',                                        /* Your custom message here                            */
                        );
                    }
                }
            } catch (Exception $ex) { // Catch anything that went wrong
                // Log::info($ex->getMessage()); //getCode()
                $notification = array(
                    'alert_type' => 'warning',                                              /* "success", "error", "warning", "info" or "question" */
                    'message' => 'Woops. Record deleted perhaps?',                          /* Your custom message here                            */
                );
            }

            /* Return response under json format */
            return response()->json([
                'notification' => $notification,
                'errors' => $errors ?? null
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
        // not needed, managed via users.delete
    // }
    
    /****************************************************************************************************************************************************************/
    /* ADDITIONAL METHODS SECTION                                                                                                                                   */
    /****************************************************************************************************************************************************************/
    // public function restore($id) //if got soft delete capability, need to use $id instead model
    // {
        // not needed, managed via users.restore
    // }

    /* Get Datatables */
    // public function getDatatable($type){
        // not needed
    // }

    /****************************************************************************************************************************************************************/
    /* API SECTION                                                                                                                                                  */
    /****************************************************************************************************************************************************************/
}
