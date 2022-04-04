<?php

namespace App\Http\Controllers\Calendar\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Calendar\Event\Calendar as controllerModel;
use App\Models\Calendar\Event\Category as optionsModel1;
use Carbon\Carbon;
use Exception;
use Response;
use Auth;
use Log;
use DB;


class CalendarController extends Controller
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
        /************************ */
        //Get available CalendarEventCategory
        $calendarEventCategory = optionsModel1::all();

        return view('calendar.event.index')
        ->with('calendarEventCategory', $calendarEventCategory);

        // //Alert::alert('Title', 'Message', 'Type'); // use this to display alert
        // //return view('template.basic.index');                    // Browse : will be using DataTables function, refer to function getDatatable($type)   
    }

    public function eventData()
    {
        $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
        $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');

        //using leftjoin, when two tables have same column name (such as id), may return column data override
        $data = controllerModel::whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)
        ->leftJoin('calendar_event_category', 'calendar_event.calendar_event_category_id', '=', 'calendar_event_category.id')
        ->select('calendar_event.*','calendar_event_category.name as category','calendar_event_category.borderColor as borderColor','calendar_event_category.backgroundColor as backgroundColor')
        ->get();

        return Response::json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // create view is integrated in index view
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
                        if($request->daterange){
                            //daterange
                            $dates = explode(' - ', $request->daterange);
                            $start_date = Carbon::createFromFormat('d/m/Y g:i A', $dates[0])->format('Y-m-d H:i:s');  //to save in database format
                            $end_date = Carbon::createFromFormat('d/m/Y g:i A', $dates[1])->format('Y-m-d H:i:s');    //to save in database format
                        }
                        $data['start'] =  $start_date;
                        $data['end'] = $end_date;
                        /* Custom data entry ends */

                        //Save via mass assignments, columns that are defined in fillable property in model
                        $saved = controllerModel::create($data);

                        //Save explicitly (relationship)
                        //$saved->category()->associate($request->template_calendar_categories_id)->save();

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
        // show view is integrated in index view
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // edit view is integrated in index view
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
                // if ($id !== null) {               //check if $id is not empty
                //     $rules['name'] .= ",$id";     //if value of field same, then ignore (only tested for 1 column only)
                // }
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
                        if($request->daterange){
                            //daterange
                            $dates = explode(' - ', $request->daterange);
                            $start_date = Carbon::createFromFormat('d/m/Y g:i A', $dates[0])->format('Y-m-d H:i:s');  //to save in database format
                            $end_date = Carbon::createFromFormat('d/m/Y g:i A', $dates[1])->format('Y-m-d H:i:s');    //to save in database format
                            
                            $data['start'] =  $start_date;
                            $data['end'] = $end_date;
                        }
                        /* For field that require reformat to comply with database , override $data array here */
                        
                        /* Custom data entry ends */

                        //Save via mass assignments, columns that are defined in fillable property in model
                        $saved = $result->update($data);

                        //Save explicitly (relationship)
                        //Log::info($request->template_calendar_categories_id);
                        //$result->category()->associate($request->template_calendar_categories_id)->save();

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
        /********************************* Custom code starts *********************************/
        /* Enable only via ajax */
        if(request()->ajax())
        {    
            /* Avoid Empty 500 Server Error with Try-Catch */
            try{ 
                $calendar = controllerModel::findOrFail($id);                                                      // will automatically redirect to page error:404 if not found
                $calendar->delete();

                $notification = array(
                    'alert_type' => 'success',                          /* "success", "error", "warning", "info" or "question" */
                    'message' => 'Event has been deleted!',                            /* Your custom message here                            */
                );

            } catch (Exception $ex) { // Catch anything that went wrong
                $notification = array(
                    'alert_type' => 'warning',                                  /* "success", "error", "warning", "info" or "question" */
                    'message' => 'Woops. Record deleted perhaps?',                      /* Your custom message here                            */
                );
            }
            
            /* Return response under json format */
            return response()->json([
                'notification' => $notification
            ]);
        }
        /********************************* Custom code ends *********************************/

        // $result = controllerModel::findOrFail($id);                                                      // will automatically redirect to page error:404 if not found
        // $result->delete(); //For both soft delete & hard delete cases
        // return redirect()->route('template.basic.index')->with(['danger' => 'Data has been deleted !']);
    }
    
    /****************************************************************************************************************************************************************/
    /* ADDITIONAL METHODS SECTION                                                                                                                                   */
    /****************************************************************************************************************************************************************/
    public function updateDroppable(Request $request, $id)
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
            try{
                //Get model data
                $result = controllerModel::findOrFail($id); //if fail, will return 404 error

                //Validator
                $rules = controllerModel::$rules2; //get rules
                //For unique record
                // if ($id !== null) {               //check if $id is not empty
                //     $rules['name'] .= ",$id";     //if value of field same, then ignore (only tested for 1 column only)
                // }
                $validator  = Validator::make(
                    $request->all(),
                    $rules,
                    controllerModel::$rulesMessages2
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
                        $data['updated_by'] = Auth::user()->name;
                        
                        /* Custom data entry starts */
                        // For field that require reformat to comply with database , override $data array here
                        //$data['column_name'] =  value;
                        $data['start'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date)->format('Y-m-d H:i:s');  //to save in database format
                        $data['end'] = Carbon::createFromFormat('d/m/Y H:i:s', $request->end_date)->format('Y-m-d H:i:s');    //to save in database format
                    
                        /* Custom data entry ends */

                        $saved = $result->update($data);
                        
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

    /****************************************************************************************************************************************************************/
    /* API SECTION                                                                                                                                                  */
    /****************************************************************************************************************************************************************/
}
