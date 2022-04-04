<?php

namespace App\Http\Controllers\Template;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Template\TemplateBasic as controllerModel;
use Carbon\Carbon;
use Exception;
use Storage;
use Alert;
use Auth;
use Log;
use DB;

class TemplateBasicController extends Controller
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
        //Alert::alert('Title', 'Message', 'Type'); // use this to display alert
        return view('template.basic.index');                    // Browse : will be using DataTables function, refer to function getDatatable($type)
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('template.basic.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* Enable only via ajax */     
        if(request()->ajax() || $request->is('api/*')){

            //Cast data from api
            if($request->is('api/*')){
                //Cast type first
                $request = $request['formValue'];       //get the array data from mobile
                $request = new Request($request);       //convert to type Request so that we can use with store method
            }

            /* Avoid Empty 500 Server Error with Try-Catch */
            //For example, creating a record that already exist will return catch notification
            try{
                //Validator
                $validator  = Validator::make(
                    $request->all(),
                    controllerModel::$rules,
                    controllerModel::$rulesMessages
                );

                //Check validator
                if ($validator->fails())
                {
                    //Log::info($validator->messages());
                    $errors =  $validator->messages();
                    $notification = array(
                        'alert_type' => 'warning',                                          /* "success", "error", "warning", "info" or "question" */
                        'message' => 'Woops. There seem to be an error with your input.',      /* Your custom message here                            */
                    );
                }else{
                    //Check for DB transaction. Rollback will be automatic if any previous transaction has failed
                    $query = DB::transaction(function()  use ($request)
                    {
                        //Create
                        $data = $request->all();
                        
                        /* Custom data entry starts */
                        // For field that require reformat to comply with database , override $data array here
                        //$data['column_name'] =  value;
                        /* Custom data entry ends */

                        //Save via mass assignments, columns that are defined in fillable property in model
                        $saved = controllerModel::create($data);

                        //Save explicitly, column that are not defined in fillable property in model
                        $saved->created_by = Auth::user()->name;
                        $saved->save();
                        
                        if( $saved )
                        {
                            return true;
                        }else{
                            throw new \Exception('Unable to save data');
                        }
                    });

                    // Check whether query successful or not
                    if($query){  //success, send a notification
                        $notification = array(
                            'alert_type' => 'success',                                      /* "success", "error", "warning", "info" or "question" */
                            'message' => 'Created!',                                        /* Your custom message here                            */
                        );
                    }
                }
            } catch (Exception $ex) { // Catch anything that went wrong
                //Log::info($ex->getMessage()); //getCode()
                $notification = array(
                    'alert_type' => 'warning',                                              /* "success", "error", "warning", "info" or "question" */
                    'message' => 'Woops. Possible duplicate record.',                       /* Your custom message here                            */
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //For soft delete
        $result = controllerModel::withTrashed()->findOrFail($id);                          //For model with softdelete, use withTrashed() helper
        //For hard delete
        //$result = controllerModel::findOrFail($id);

        return view('template.basic.show', compact('result'));                              //to list given permissions to this role
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = controllerModel::findOrFail($id);                                                      // will automatically redirect to page error:404 if not found

        return view('template.basic.edit', compact('result'));  //to populate select multiple
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
                $rules = controllerModel::$rules; //get rules
                //For unique record
                if ($id !== null) {               //check if $id is not empty
                    $rules['name'] .= ",$id";     //if value of field same, then ignore (only tested for 1 column only)
                }
                $validator  = Validator::make(
                    $request->all(),
                    $rules,
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
                //Log::info($ex->getMessage()); //getCode()
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
    public function destroy($id)
    {
        $result = controllerModel::findOrFail($id);                                                      // will automatically redirect to page error:404 if not found
        $result->delete(); //For both soft delete & hard delete cases
        return redirect()->route('template.basic.index')->with(['danger' => 'Data has been deleted !']);
    }
    
    /****************************************************************************************************************************************************************/
    /* ADDITIONAL METHODS SECTION                                                                                                                                   */
    /****************************************************************************************************************************************************************/
    public function restore($id) //if got soft delete capability, need to use $id instead model
    {
        /* We do not use ajax here because we use modal-form to delete record */
        $result = controllerModel::withTrashed()->findOrFail($id);                                       // will automatically redirect to page error:404 if not found
        $result->restore();
        return redirect()->route('template.basic.index')->with(['info' => 'Data has been restored !']);
    }

    /* Get Datatables */
    public function getDatatable($type){
        /* Enable only via ajax */
        if(request()->ajax())
        {
            /* Change model query based on user selection */
            if($type ==='active'){
                $model = controllerModel::all();              //display all data, excluding softdeleted records
            }
            elseif($type ==='all'){
                $model = controllerModel::withTrashed();      //display all data, including softdeleted records
            }
            elseif($type ==='trashed'){
                $model = controllerModel::onlyTrashed();      //display softdeleted records only
            }
            else{
                $model = controllerModel::all();              //by default, return this
            }

            //return DataTables
            return Datatables::of($model)
                    ->addColumn('action', function($data){
                        $button = '<a href="/template/basic/'.$data->id.'" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i><span> Show</a>';
                        if(is_null($data->deleted_at)) {
                            $button .= '&nbsp;&nbsp;';
                            $button .= '<a href="/template/basic/'.$data->id.'/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i><span> Edit</a>';
                        }
                        return $button;
                    })
                    ->rawColumns(['action'])
                    ->addIndexColumn()              //enable DT_RowIndex on view, incremental index
                    //->with('comments', 20)        /add extra data
                    ->make(true);
        }
        return view('template.basic.index');
    }

    /****************************************************************************************************************************************************************/
    /* API SECTION                                                                                                                                                  */
    /****************************************************************************************************************************************************************/

    public function apiGetRoles(){
        $model = controllerModel::withTrashed()->get()->toArray();              //display all data, excluding softdeleted records
        return response()->json([
            'result'=> array_values($model),
        ], 200);
    }

    public function apiDelete($id) //api calls cannot simply receive $id as params
    {
        $model = controllerModel::find($id);            //for api,cannot use findOrFail because it will return directly 
        if(!$model){
            $notification = array(
                'alert_type' => 'danger',                                      /* "success", "error", "warning", "info" or "question" */
                'message' => 'Woops. Record already deleted?',                 /* Your custom message here                            */
            );
        }else{
            $model->delete();                                                  //hard delete or soft delete
            $notification = array(
                'alert_type' => 'success',                                     /* "success", "error", "warning", "info" or "question" */
                'message' => 'Deleted!',                                       /* Your custom message here                            */
            );
        }
        /* Return response under json format */
        return response()->json([
            'notification' => $notification,
            'errors' => $errors ?? null
        ]);
    }
}
