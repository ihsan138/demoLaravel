<?php

namespace App\Http\Controllers\Calendar\Calibration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Calendar\Calibration\Calendar as controllerModel;
use App\Models\Calendar\Calibration\Category as optionsModel1;
use Carbon\Carbon;
use Exception;
use Response;
use Auth;
use Log;
use DB;

class CalendarController extends Controller
{
    // https://www.textmagic.com/free-tools/rrule-generator
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
        //Get available CalendarCalibrationCategory
        $calendarCalibrationCategory = optionsModel1::all();

        return view('calendar.calibration.index')
        ->with('calendarCalibrationCategory', $calendarCalibrationCategory);

        // //Alert::alert('Title', 'Message', 'Type'); // use this to display alert
        // //return view('template.basic.index');                    // Browse : will be using DataTables function, refer to function getDatatable($type)   
    }

    public function eventRecurringData()
    {
        //https://jakubroztocil.github.io/rrule/
        
        //for recurring events, start and end columns must be null value. if not error.
        //rrule can be accessed by console.log(data.event._def.recurringDef) but a bit cryptic. so we'll use as def_rrule to access it by extendedProps instead

        //using leftjoin, when two tables have same column name (such as id), may return column data override
        $data = controllerModel::leftJoin('calendar_calibration_category', 'calendar_calibration.calendar_calibration_category_id', '=', 'calendar_calibration_category.id')
        ->select('calendar_calibration.*','calendar_calibration.rrule as def_rrule','calendar_calibration_category.name as category','calendar_calibration_category.borderColor as borderColor','calendar_calibration_category.backgroundColor as backgroundColor')
        ->get()->toArray();

        for($count = 0; $count < count($data); $count++){
            $rrule = $data[$count]['rrule'];
            // Log::info('here');
            // Log::info($rrule);
            // $str_rr = '{"freq": "weekly","dtstart": "2021-09-01"}';
            $str_rr = $rrule;
            // $rrule = json_decode($str_rr);
            $rrule = json_decode($str_rr, true);
            $data[$count]['rrule']=$rrule;

        }
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

                        /******* RRULE STARTS *******/
                        $frequency = $request->frequency;

                        $rrule = [
                            'freq' => $frequency,
                        ];

                        switch ($frequency) {
                            // case 'Pending': //never happened
                            //     break;
                            case 'yearly':
                                $bymonth = (int)$request->yearly_bymonth;
                                $bymonthday = (int)$request->yearly_bymonthday;

                                $rrule += [
                                    'bymonth' => $bymonth,
                                    'bymonthday' => $bymonthday,
                                ];
                                break;
                            case 'monthly':
                                $bymonthday = (int)$request->monthly_bymonthday;
                                $interval = (int)$request->interval;

                                $rrule += [
                                    'bymonthday' => $bymonthday,
                                    'interval' => $interval,
                                ];
                                break;
                            case 'weekly':
                                $wkst = $request->weekly_wkst;
                                $interval = (int)$request->interval;
                                
                                $rrule += [
                                    'wkst' => $wkst,
                                    'interval' => $interval,
                                ];
                                break;
                            case 'daily':
                                $interval = (int)$request->interval;

                                $rrule += [
                                    'interval' => $interval,
                                ];
                                break;
                            default:
                                break;
                        }

                        $end = $request->end;

                        switch ($end) {
                            case 'never':
                                break;
                            case 'after':
                                $count = $request->count;

                                $rrule += [
                                    'count' => $count,
                                ];
                                break;
                            case 'ondate':
                                //datepicker
                                if ($request->until) {     //need to check this because Carbon::createFromFormat will crash if value is null
                                    $until = Carbon::createFromFormat('d/m/Y', $request->until)->format('Y-m-d');

                                    $rrule += [
                                        'until' => $until,
                                    ];
                                }
                                break;
                            default:
                                break;
                        }

                        // //convert to json string to store in rrule column
                        // $rrule = [
                        //     'freq' => $frequency,
                        //     'count' => $count ?? '',
                        //     'interval' => $interval,
                        //     'dtstart' => $dtstart,
                        //     'until' => $until
                        // ];

                        $data['rrule'] = json_encode($rrule);

                        /******* RRULE ENDS *******/

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
                Log::info($ex->getMessage()); //getCode()
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
                        
                        
                        /******* RRULE STARTS *******/
                        $frequency = $request->frequency;

                        $rrule = [
                            'freq' => $frequency,
                        ];

                        switch ($frequency) {
                            // case 'Pending': //never happened
                            //     break;
                            case 'yearly':
                                $bymonth = (int)$request->yearly_bymonth;
                                $bymonthday = (int)$request->yearly_bymonthday;

                                $rrule += [
                                    'bymonth' => $bymonth,
                                    'bymonthday' => $bymonthday,
                                ];
                                break;
                            case 'monthly':
                                $bymonthday = (int)$request->monthly_bymonthday;
                                $interval = (int)$request->interval;

                                $rrule += [
                                    'bymonthday' => $bymonthday,
                                    'interval' => $interval,
                                ];
                                break;
                            case 'weekly':
                                $wkst = $request->weekly_wkst;
                                $interval = (int)$request->interval;
                                
                                $rrule += [
                                    'wkst' => $wkst,
                                    'interval' => $interval,
                                ];
                                break;
                            case 'daily':
                                $interval = (int)$request->interval;

                                $rrule += [
                                    'interval' => $interval,
                                ];
                                break;
                            default:
                                break;
                        }

                        $end = $request->end;

                        switch ($end) {
                            case 'never':
                                break;
                            case 'after':
                                $count = $request->count;

                                $rrule += [
                                    'count' => $count,
                                ];
                                break;
                            case 'ondate':
                                //datepicker
                                if ($request->until) {     //need to check this because Carbon::createFromFormat will crash if value is null
                                    $until = Carbon::createFromFormat('d/m/Y', $request->until)->format('Y-m-d');

                                    $rrule += [
                                        'until' => $until,
                                    ];
                                }
                                break;
                            default:
                                break;
                        }

                        // //convert to json string to store in rrule column
                        // $rrule = [
                        //     'freq' => $frequency,
                        //     'count' => $count ?? '',
                        //     'interval' => $interval,
                        //     'dtstart' => $dtstart,
                        //     'until' => $until
                        // ];

                        $data['rrule'] = json_encode($rrule);

                        /****** RRULE ENDS *******/
                        
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
    /* Get Datatables */
    public function getDatatable($type){
        /* Enable only via ajax */
        if(request()->ajax())
        {
            /* Change model query based on user selection */
            if($type ==='active'){
                $model = controllerModel::with('category')->get();              //display all data, excluding softdeleted records
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

            //Find time left till next event
            Log::info($model);

            //return DataTables
            return Datatables::of($model)
                    ->addIndexColumn()              //enable DT_RowIndex on view, incremental index
                    //->with('comments', 20)        /add extra data
                    ->make(true);
        }
        return view('calendar.calibration.index');
    }

    /****************************************************************************************************************************************************************/
    /* API SECTION                                                                                                                                                  */
    /****************************************************************************************************************************************************************/
}
