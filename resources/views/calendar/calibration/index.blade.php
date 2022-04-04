@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    
    @include('calendar.navigation-bar')

    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Calibration
                </div>
                <div class="card-body">
                    <a href="" data-target="#modal-create" data-toggle="modal" class="btn btn-success btn-sm"><i class="fas fa-plus-circle"></i> New Calibration</a>
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
            <div class="card">
                <div class="card-header">
                    Legends
                </div>
                <div class="card-body">
                    @foreach($calendarCalibrationCategory as $category)
                        <div class="external-event bg-{{ $category->borderColor }}">{{ $category->name }}</div>
                    @endforeach
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    Calendar Calibration
                </div>
                <div class="card-body">
                    <div id='calendar'></div>  
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
        </div>
    </div>
    <!-- <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Calibration Calendar Table
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div id="show-type"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-bordered table-hover" role="grid" width="100%" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>RRule</th>
                                        <th>Created At</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  -->
</div>

<!-- MODALS -->
<div class="modal fade" id="modal-create" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create Calibration</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formCreate">
                @csrf 
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">Calibration Title</label>
                    <div class="col-md-10">
                        <input id="create-title" name="title" type="text" class="form-control" value="Event title" required="required">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <!-- Select -->
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">Category</label>
                    <div class="col-md-10">
                        <select class="form-control" id="create-calendar_calibration_category_id" name="calendar_calibration_category_id" required="required">
                        @foreach($calendarCalibrationCategory as $category)
                            <option value='{{ $category->id }}'>{{ $category->name }}</option>	
                        @endforeach				
                        </select>
                    </div>
                </div>
                <!-- Select -->

                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Repeat</label>
                            <div class="col-md-8">
                                <select class="form-control" id="create-frequency" name="frequency" required="required">
                                    <option value='yearly'>Yearly</option>	
                                    <option value='monthly'>Monthly</option>	
                                    <option value='weekly'>Weekly</option>	
                                    <option value='daily'>Daily</option>	
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="create-interval-div" class="col-md-6 create-options">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Every</label>
                            <div class="col-md-4">
                                <input id="create-interval" name="interval" type="number" value="1" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div id="create-interval-text">
                                <label for="name" class="col-md-3 col-form-label text-md-right"></label>
                            </div>
                        </div>
                    </div>
                </div> 
                <div id="create-yearly-options" class="form-group row create-options">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">ON</label>
                            <div class="col-md-8">
                                <select class="form-control" id="create-yearly-bymonth" name="yearly_bymonth" required="required">
                                    <option value='1'>January</option>	
                                    <option value='2'>February</option>	
                                    <option value='3'>March</option>	
                                    <option value='4'>April</option>	
                                    <option value='5'>May</option>	
                                    <option value='6'>June</option>	
                                    <option value='7'>July</option>	
                                    <option value='8'>August</option>	
                                    <option value='9'>September</option>	
                                    <option value='10'>October</option>	
                                    <option value='11'>November</option>	
                                    <option value='12'>December</option>	
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="create-interval-div" class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <select class="form-control" id="create-yearly-bymonthday" name="yearly_bymonthday" required="required">
                                  @for ($i = 1; $i <= 31; $i++)
                                        <option value='{{ $i }}'>{{ $i }}</option>
                                    @endfor	
                                </select>
                            </div>
                        </div>
                    </div>
                </div> 
                <div id="create-monthly-options" class="form-group row create-options">
                    <div id="create-monthly-div" class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">ON DAY</label>
                            <div class="col-md-8">
                                <select class="form-control" id="create-monthly-bymonthday" name="monthly_bymonthday" required="required">
                                  @for ($i = 1; $i <= 31; $i++)
                                        <option value='{{ $i }}'>{{ $i }}</option>
                                    @endfor	
                                </select>
                            </div>
                        </div>
                    </div>
                </div> 
                <div id="create-weekly-options" class="form-group row create-options">
                    <div id="create-weekly-div" class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">ON DAY</label>
                            <div class="col-md-8">
                                <select class="form-control" id="create-weekly-wkst" name="weekly_wkst" required="required">
                                    <option value='SU'>Sunday</option>	
                                    <option value='MO'>Monday</option>	
                                    <option value='TU'>Tuesday</option>	
                                    <option value='WE'>Wednesday</option>	
                                    <option value='TH'>Thursday</option>	
                                    <option value='FR'>Friday</option>	
                                    <option value='SA'>Saturday</option>	
                                </select>
                            </div>
                        </div>
                    </div>
                </div> 
                <hr>
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">End</label>
                            <div class="col-md-8">
                                <select class="form-control" id="create-end" name="end" required="required">
                                    <option value='never'>Never</option>	
                                    <option value='after'>After</option>	
                                    <option value='ondate'>On Date</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="create-count-div" class="col-md-6 create-end-options">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <input id="create-count" name="count" type="number" value="1" class="form-control">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div id="create-sessions-text">
                                <label for="name" class="col-md-3 col-form-label text-md-right">Sessions</label>
                            </div>
                        </div>
                    </div>
                    <div id="create-until-div" class="col-md-6 create-end-options">
                        <!-- Date Picker -->
                        <div class="form-group row">
                            <div class="col-md-10">
                                <div class="input-group date" id="create-until" data-target-input="nearest">
                                <input type="text" name="until" class="form-control datetimepicker-input" data-target="#create-until"/>
                                <div class="invalid-feedback"></div>
                                    <div class="input-group-append" data-target="#create-until" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <!-- /.form group -->
                    </div>
                </div> 
        </div>
        <div class="modal-footer justify-content-between">
                <button id="submitCreate" type="submit" class="btn btn-success">Create</button>
            </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODALS -->
<div class="modal fade" id="modal-edit" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Calibration</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    @csrf 
                    @method('PUT')

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">Calibration Title</label>
                        <div class="col-md-10">
                            <input id="edit-title" name="title" type="text" class="form-control" required="required">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <!-- Select -->
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">Category</label>
                        <div class="col-md-10">
                            <select class="form-control" id="edit-calendar_calibration_category_id" name="calendar_calibration_category_id" required="required">
                            @foreach($calendarCalibrationCategory as $category)
                                <option value='{{ $category->id }}'>{{ $category->name }}</option>	
                            @endforeach				
                            </select>
                        </div>
                    </div>
                    <!-- Select -->

                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Repeat</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-frequency" name="frequency" required="required">
                                        <option value='yearly'>Yearly</option>	
                                        <option value='monthly'>Monthly</option>	
                                        <option value='weekly'>Weekly</option>	
                                        <option value='daily'>Daily</option>	
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="edit-interval-div" class="col-md-6 edit-options">
                            <div class="form-group row">
                                <label for="name" class="col-md-3 col-form-label text-md-right">Every</label>
                                <div class="col-md-4">
                                    <input id="edit-interval" name="interval" type="number" value="1" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div id="edit-interval-text">
                                    <label for="name" class="col-md-3 col-form-label text-md-right"></label>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div id="edit-yearly-options" class="form-group row edit-options">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ON</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-yearly-bymonth" name="yearly_bymonth" required="required">
                                        <option value='1'>January</option>	
                                        <option value='2'>February</option>	
                                        <option value='3'>March</option>	
                                        <option value='4'>April</option>	
                                        <option value='5'>May</option>	
                                        <option value='6'>June</option>	
                                        <option value='7'>July</option>	
                                        <option value='8'>August</option>	
                                        <option value='9'>September</option>	
                                        <option value='10'>October</option>	
                                        <option value='11'>November</option>	
                                        <option value='12'>December</option>	
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="edit-interval-div" class="col-md-6">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-yearly-bymonthday" name="yearly_bymonthday" required="required">
                                        @for ($i = 1; $i <= 31; $i++)
                                            <option value='{{ $i }}'>{{ $i }}</option>
                                        @endfor	
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div id="edit-monthly-options" class="form-group row edit-options">
                        <div id="edit-monthly-div" class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ON DAY</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-monthly-bymonthday" name="monthly_bymonthday" required="required">
                                    @for ($i = 1; $i <= 31; $i++)
                                            <option value='{{ $i }}'>{{ $i }}</option>
                                        @endfor	
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div id="edit-weekly-options" class="form-group row edit-options">
                        <div id="edit-weekly-div" class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">ON DAY</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-weekly-wkst" name="weekly_wkst" required="required">
                                        <option value='SU'>Sunday</option>	
                                        <option value='MO'>Monday</option>	
                                        <option value='TU'>Tuesday</option>	
                                        <option value='WE'>Wednesday</option>	
                                        <option value='TH'>Thursday</option>	
                                        <option value='FR'>Friday</option>	
                                        <option value='SA'>Saturday</option>	
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <hr>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">End</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="edit-end" name="end" required="required">
                                        <option value='never'>Never</option>	
                                        <option value='after'>After</option>	
                                        <option value='ondate'>On Date</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="edit-count-div" class="col-md-6 edit-end-options">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input id="edit-count" name="count" type="number" value="1" class="form-control">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div id="edit-sessions-text">
                                    <label for="name" class="col-md-3 col-form-label text-md-right">Sessions</label>
                                </div>
                            </div>
                        </div>
                        <div id="edit-until-div" class="col-md-6 edit-end-options">
                            <!-- Date Picker -->
                            <div class="form-group row">
                                <div class="col-md-10">
                                    <div class="input-group date" id="edit-until" data-target-input="nearest">
                                    <input type="text" name="until" class="form-control datetimepicker-input" data-target="#edit-until"/>
                                    <div class="invalid-feedback"></div>
                                        <div class="input-group-append" data-target="#edit-until" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                    </div> 
            </div>
            <div class="modal-footer justify-content-between">
                    <button id="submitEdit" type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODALS -->
<div class="modal fade" id="modal-show" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Calibration Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <dl class="dl-horizontal">
                    <dt>Calibration Title</dt>
                    <dd id="show-title"></dd>
                    <dt>Calibration Category</dt>
                    <dd id="show-category"></dd>
                    <dt>Rrule</dt>
                    <dd id="show-rrule"></dd>
                    <dt>Created By</dt>
                    <dd id="show-created-by"></dd>
                    <dt>Updated By</dt>
                    <dd id="show-updated-by"></dd>
                    </dl>
                </div>
            </div>
            <div class="modal-footer">
                <button id="edit-calibration" type="submit" class="btn btn-warning btn-group">Edit</button>
                <button id="delete-calibration" type="submit" class="btn btn-danger btn-group">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- MODALS -->
<div class="modal fade" id="modal-delete" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm delete?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">Do you wish to delete&nbsp;<div id="delete-title"></div>&nbsp;?</div>
                <form id="formDelete" method="POST">
                    @csrf 
                    @method('DELETE')
            </div>
            <div class="modal-footer justify-content-between">
                    <button id="submitDelete" type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@section('js')
<script>
    $(document).ready(function () {
        // For datepicker input, need to add extra fields for value validation
        /****************************************************************************************************************************************/
        /*           DATETIMEPICKER : https://eonasdan.github.io/bootstrap-datetimepicker/                                                      */
        /****************************************************************************************************************************************/
        //Datepicker definition must follow this order (dd/mm/yyyy then mm/yyyy then yyyy)
        $('#create-until').datetimepicker({
            viewMode: 'days',  //min view mode/start display from (times, days, months, years, decades), for example month will auto select current year as selected year
            //minViewMode: 'year',
            format: 'DD/MM/YYYY',   //date-month-year
        });

        //Datepicker definition must follow this order (dd/mm/yyyy then mm/yyyy then yyyy)
        $('#edit-until').datetimepicker({
            viewMode: 'days',  //min view mode/start display from (times, days, months, years, decades), for example month will auto select current year as selected year
            //minViewMode: 'year',
            format: 'DD/MM/YYYY',   //date-month-year
        });

        /****************************************************************************************************************************************/
        /*           CALENDAR                                                                                                                   */
        /****************************************************************************************************************************************/

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            editable: false,   //for recurring calendar, we disable this option
            droppable: false,  //for recurring calendar, we disable this option
            displayEventTime:false, //for recurring calendar, we disable this option
            displayEventEnd :false, //for recurring calendar, we disable this option
            
            // https://fullcalendar.io/docs/date-formatting
            titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                day: '2-digit',
                year: 'numeric',
                month: 'long',
            },
            headerToolbar: {
                center: 'listWeek,dayGridMonth' // buttons for switching between views
                // center: 'dayGridMonth,timeGridWeek,timeGridFourDay' // buttons for switching between views
            },
            initialView : 'list',
            // views: {
            //     timeGridFourDay: {
            //     type: 'timeGrid',
            //     duration: { days: 90 },
            //     buttonText: '90 day'
            //     }
            // },
            // header:{
            //     left:'prev,next today',
            //     center:'title',
            //     right:'month,listMonth,listWeek,listDay'
            // },
            views: {
                list: {
                    duration: { days: 90 },
                    titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                        day: '2-digit',
                        year: 'numeric',
                        month: 'long',

                    },
                }
            },
            eventTimeFormat: {
                hour: "2-digit",
                minute: "2-digit",
                meridiem: "short",
            },
            // timeFormat: 'hh:mm a',
            buttonText: {
                dayGridMonth: 'Month',
                timeGridWeek: 'Week',
                listWeek: 'List Week',
            },
            events: "/calendar/calibration/eventRecurringData", // SITEURL + "fullcalendar",
            // eventSources: [
            //     // your event source
            //     {
            //         url: '/calendar/calibration/eventData', // use the `url` property
            //     },
            //     {
            //         url: '/calendar/event/eventData', // use the `url` property
            //     }
            // ],
            // events: [
            //     {
            //         title  : 'event1',
            //         start  : '2021-09-17'
            //         },
            //         {
            //         title  : 'event2',
            //         start  : '2021-09-28',
            //         end    : '2021-09-29'
            //         },
            //     {
            //         title: 'my recurring event',
            //         //recurring rule
            //         rrule: {
            //             freq: 'weekly',
            //             dtstart: '2021-09-01',
            //             until: '2022-10-01',
            //         },
            //         //exclusion rule
            //         // exrule: { // will also accept an array of these objects
            //         //     freq: 'weekly',
            //         //     dtstart: '2021-09-15',
            //         //     until: '2021-09-21'
            //         // }
            //     }
            // ],
            // {
            // "rrule": {
            //         "freq": "weekly",
            //         "dtstart": "2021-09-01"
            //     }
            // }
                    // {"freq": "weekly","dtstart": "2021-09-01"}
            // eventRender: function (event, element, view) {
            //     if (event.allDay === 'true') {
            //         event.allDay = true;
            //     } else {
            //         event.allDay = false;
            //     }
            // },
            
            // selectable: true,
            // selectHelper: true,
            // select: function (data) {
            //     $('#modal-create').modal('show');
            //     $('#create-daterange').data('daterangepicker').setStartDate(data.start); //'03/01/2014'
            //     $('#create-daterange').data('daterangepicker').setEndDate(data.end);
            // },
            // eventClick:function(data) /* Fires when clicking an event */
            
            eventClick: function(data)
            {
                // console.log(data);
                var id = data.event.id;
                var title = data.event.title;
                var category = data.event.extendedProps.category;
                var calendar_calibration_category_id = data.event.extendedProps.calendar_calibration_category_id;
                // var start = FullCalendar.formatDate(data.event.start, "DD/MM/YYYY HH:mm:ss");    //previous version fullcalendar
                // var end = $.fullCalendar.formatDate(data.event.end, "DD/MM/YYYY HH:mm:ss");      //previous version fullcalendar
                var def_rrule = data.event.extendedProps.def_rrule;

                var rrule = def_rrule;
                var created_by = data.event.extendedProps.created_by;
                var updated_by = data.event.extendedProps.updated_by;

                //Get specific rule
                var rrule_obj = JSON.parse(def_rrule);
                var freq = rrule_obj['freq'];

                //Show calibration
                $('#show-title').html(title);
                $('#show-category').html(category);
                $('#show-rrule').html(rrule);
                $('#show-created-by').html(created_by);
                $('#show-updated-by').html(updated_by);
                $('#modal-show').modal('show');
                
                //Edit calibration
                $("#edit-calibration" ).unbind();                         //need to unbind first, if not recurring click will occur
                $('#edit-calibration').on('click', function(){
                    $('#modal-show').modal('hide');
                    $('#modal-edit').modal('show');
                    $('#edit-title').val(title);
                    $('#edit-calendar_calibration_category_id').val(calendar_calibration_category_id);


                    /****** recurring parameters start ******/
                    $('#edit-frequency').val(rrule_obj['freq']).trigger('change');

                    $('.edit-options').hide(); //hide every options
                    switch(freq) {
                        case 'yearly':
                            $('#edit-yearly-options').show();

                            //set value
                            $('#edit-yearly-bymonth').val(rrule_obj['bymonth']).trigger('change');
                            $('#edit-yearly-bymonthday').val(rrule_obj['bymonthday']).trigger('change');
                            break;
                        case 'monthly':
                            $('#edit-interval-div').show();
                            $('#edit-monthly-options').show();

                            $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">month(s)</label>');

                            //set value
                            $('#edit-monthly-bymonthday').val(rrule_obj['bymonthday']).trigger('change');
                            $('#edit-interval').val(rrule_obj['interval']);
                            break;
                        case 'weekly':
                            $('#edit-interval-div').show();
                            $('#edit-weekly-options').show();

                            $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">week(s)</label>');

                            //set value
                            $('#edit-weekly-wkst').val(rrule_obj['wkst']).trigger('change');
                            $('#edit-interval').val(rrule_obj['interval']);
                            break;
                        case 'daily':
                            $('#edit-interval-div').show();

                            $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">day(s)</label>');
                            
                            //set value
                            $('#edit-interval').val(rrule_obj['interval']);
                            break;
                        default:
                            // break
                            break;
                    }

                    //end parameter
                    
                    /****** recurring parameters ends ******/

                    editForm(id);
                });

                //Delete calibration
                $("#delete-calibration" ).unbind();                         //need to unbind first, if not recurring click will occur
                $('#delete-calibration').on('click', function(){
                    $('#modal-show').modal('hide');
                    $('#modal-delete').modal('show');
                    $('#delete-title').html(title);
                    deleteForm(id);
                });
            },
            // eventDrop:function(data)
            // {
            //     var id = data.event.id;
            //     var title = data.event.title;
            //     // var start_date = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");     //previous version fullcalendar
            //     // var end_date = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");         //previous version fullcalendar
            //     var start_date =$.fullCalendar.moment(data.event.start).format('DD/MM/YYYY HH:mm:ss');
            //     var end_date =$.fullCalendar.moment(data.event.end).format('DD/MM/YYYY HH:mm:ss');

            //     //create FormData
            //     var formData = new FormData();
            //     formData.append('id', id);
            //     formData.append('title', title);
            //     formData.append('start_date', start_date);
            //     formData.append('end_date', end_date);

            //     //call to ajax
            //     $.ajax({                                         
            //         url: '/calendar/calibration/updateDroppable/'+id,
            //         type: 'POST',
            //         headers: {'X-CSRF-TOKEN': '{{csrf_token()}}' },     //important to add here
            //         data: formData,
            //         success: function (response) {
            //             if(response.notification){
            //                 if(response.notification.alert_type == 'success'){
            //                     // calendar.fullCalendar('refetchEvents'); //refetch only if success, FullCalendar 3.10.2
            //                     calendar.refetchEvents();                  //required for FullCalendar 5.5.0
            //                 }
            //                 toastr.fire({                               //always show toast
            //                     icon: response.notification.alert_type,
            //                     title: response.notification.message,
            //                 });
            //             }
            //         },
            //         contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
            //         processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
            //     });

            // },
            // eventResize: function(info) {
            // },
        });
        calendar.render(); //required for FullCalendar 5.5.0


        /****************************************************************** CREATE FORMS ******************************************************************/
        $('.create-options').hide(); //hide every options
        $('#create-yearly-options').show(); //by default we select yearly option, thus show
        

        $("#create-frequency").on("change", function() {
            switch(this.value) {
                case 'yearly':
                    $('.create-options').hide(); //hide every options
                    $('#create-yearly-options').show();
                    break;
                case 'monthly':
                    $('.create-options').hide(); //hide every options

                    $('#create-interval-div').show();
                    $('#create-monthly-options').show();

                    $('#create-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">month(s)</label>');
                    break;
                case 'weekly':
                    $('.create-options').hide(); //hide every options

                    $('#create-interval-div').show();
                    $('#create-weekly-options').show();

                    $('#create-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">week(s)</label>');
                    break;
                case 'daily':
                    $('.create-options').hide(); //hide every options

                    $('#create-interval-div').show();

                    $('#create-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">day(s)</label>');
                    break;
                default:
                    // break
                    break;
            }
        });

        $('.create-end-options').hide(); //hide every options        
        $("#create-end").on("change", function() {
            switch(this.value) {
                case 'never':
                    $('.create-end-options').hide(); //hide every end options
                    break;
                case 'after':
                    $('.create-end-options').hide(); //hide every end options

                    $('#create-count-div').show();
                    break;
                case 'ondate':
                    $('.create-end-options').hide(); //hide every end options

                    $('#create-until-div').show();

                    break;
                default:
                    // break
                    break;
            }
        });

        /****************************************************************** EDIT FORMS ******************************************************************/
        $('.edit-options').hide(); //hide every options
        $('#edit-yearly-options').show(); //by default we select yearly option, thus show
        

        $("#edit-frequency").on("change", function() {
            switch(this.value) {
                case 'yearly':
                    $('.edit-options').hide(); //hide every options
                    $('#edit-yearly-options').show();
                    break;
                case 'monthly':
                    $('.edit-options').hide(); //hide every options

                    $('#edit-interval-div').show();
                    $('#edit-monthly-options').show();

                    $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">month(s)</label>');
                    break;
                case 'weekly':
                    $('.edit-options').hide(); //hide every options

                    $('#edit-interval-div').show();
                    $('#edit-weekly-options').show();

                    $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">week(s)</label>');
                    break;
                case 'daily':
                    $('.edit-options').hide(); //hide every options

                    $('#edit-interval-div').show();

                    $('#edit-interval-text').html('<label for="name" class="col-md-12 col-form-label text-md-right">day(s)</label>');
                    break;
                default:
                    // break
                    break;
            }
        });

        $('.edit-end-options').hide(); //hide every options        
        $("#edit-end").on("change", function() {
            switch(this.value) {
                case 'never':
                    $('.edit-end-options').hide(); //hide every end options
                    break;
                case 'after':
                    $('.edit-end-options').hide(); //hide every end options

                    $('#edit-count-div').show();
                    break;
                case 'ondate':
                    $('.edit-end-options').hide(); //hide every end options

                    $('#edit-until-div').show();

                    break;
                default:
                    // break
                    break;
            }
        });

        /****************************************************************** SUBMIT FORMS ******************************************************************/
        /* form submission */ 

        //create
        //does not require to be in a function, because no need to pass any parameter.
        //submit form
        $("#formCreate" ).unbind();                                 //need to unbind first, if not recurring click will occur
        $('#formCreate').submit(function (e) {
            e.preventDefault()                                      // prevent the form from 'submitting'
                var currentForm = this;                         
                //var url = e.target.action                         // get the target
                var url = '/calendar/calibration'                              // get the target, use this because generic
                var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
                $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend:function(){
                        $('#submitCreate').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
                    },
                    success: function (response) {
                        $('#submitCreate').attr('disabled', false);
                        $(":input").removeClass("is-invalid");                                                              //remove all input is-invalid class
                        if(response.errors){                                                                                // Checks for error
                            //alert(i);
                            $.each(response.errors, function (i){                                                           //response.errors is JSON object, console.log(i);
                                $('input[name="'+i+'"]').addClass("is-invalid");                                            //searh for input with name = i, then add class is-invalid
                                $.each(response.errors[i], function (key, val) {
                                    $('input[name="'+i+'"]').closest('.form-group').find('.invalid-feedback').html(val);    //put validation error message
                                });
                            });
                        }
                        if(response.notification){
                            if(response.notification.alert_type == 'success'){
                                $('#modal-create').modal('hide');       //hide modal only if success
                                currentForm.reset();                    //reset form only if success
                                // calendar.fullCalendar('refetchEvents'); //refetch only if success, FullCalendar 3.10.2
                                calendar.refetchEvents();                  //required for FullCalendar 5.5.0

                                $('#datatable').DataTable().draw(); //redraw table
                            }
                            toastr.fire({                           //always show toast
                                icon: response.notification.alert_type,
                                title: response.notification.message,
                            });
                        }
                    },
                    contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
                    processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
                });
        });

        //edit
        function editForm(id){
            //submit form
            $("#formEdit" ).unbind();                               //need to unbind first, if not recurring click will occur
            $('#formEdit').submit(function (e) {
                e.preventDefault()                                  // prevent the form from 'submitting'
                var currentForm = this;                         
                //var url = e.target.action                         // get the target
                var url = '/calendar/calibration/'+id                          // get the target, use this because generic
                var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
                $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend:function(){
                        $('#submitEdit').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
                    },
                    success: function (response) {
                        $('#submitEdit').attr('disabled', false);
                        $(":input").removeClass("is-invalid");                                                              //remove all input is-invalid class
                        if(response.errors){                                                                                // Checks for error
                            $.each(response.errors, function (i){                                                           //response.errors is JSON object, console.log(i);
                                $('input[name="'+i+'"]').addClass("is-invalid");                                            //searh for input with name = i, then add class is-invalid
                                $.each(response.errors[i], function (key, val) {
                                    $('input[name="'+i+'"]').closest('.form-group').find('.invalid-feedback').html(val);    //put validation error message
                                });
                            });
                        }
                        if(response.notification){                  
                            if(response.notification.alert_type == 'success'){
                                $('#modal-edit').modal('hide');     //hide modal only if success
                                //currentForm.reset();              //for edit, no need to reset form
                                // calendar.fullCalendar('refetchEvents'); //refetch only if success, FullCalendar 3.10.2
                                calendar.refetchEvents();                  //required for FullCalendar 5.5.0
                                
                                $('#datatable').DataTable().draw(); //redraw table
                            }
                            toastr.fire({                           //always show toast
                                icon: response.notification.alert_type,
                                title: response.notification.message,
                            });
                        }
                    },
                    contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
                    processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
                });
            });
        }

        //delete
        function deleteForm(id){
            //submit form
            $("#formDelete" ).unbind();                             //need to unbind first, if not recurring click will occur
            $('#formDelete').submit(function (e) {
                e.preventDefault()                                  // prevent the form from 'submitting'
                var currentForm = this;                         
                //var url = e.target.action                         // get the target
                var url = '/calendar/calibration/'+id                    // get the target, use this because generic
                var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
                $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
                    url: url,
                    type: 'POST',
                    data: formData,
                    beforeSend:function(){
                        $('#submitDelete').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
                    },
                    success: function (response) {
                        $('#submitDelete').attr('disabled', false);     //remove all input is-invalid class                          
                        $('#modal-delete').modal('hide');               //hide modal only if success        
                        // calendar.fullCalendar('refetchEvents'); //refetch only if success, FullCalendar 3.10.2
                        calendar.refetchEvents();                  //required for FullCalendar 5.5.0
                        
                        $('#datatable').DataTable().draw(); //redraw table

                        if(response.notification){       
                            toastr.fire({                           //always show toast
                                icon: response.notification.alert_type,
                                title: response.notification.message,
                            });
                        }
                    },
                    contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
                    processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
                });
            });
        }

        //For datatable
        myFunction('active');                                            //init, run once
    });
    </script>

<script>
    function myFunction(type){
        
        //$('#show-type').html('Showing ' + type +' records');
        var table = $('#datatable').DataTable();                             //get DataTable object. DataTable ({}) means DataTable trying to init.
        table.destroy();                                                     //destroy table first before calling ajax (if not, initialise error)
        
        $('#datatable')
            .on('processing.dt', function(e, settings, processing) { //when processing datatables, show pace instead of 'processing'
                if (processing) {
                    Pace.restart();
                    Pace.bar.render();
                } 
            })
            .DataTable({
            dom: 'BRSPQlftip',                                          //header element
                                                                        //default option: l - lengthmenu, f - filtering input, t - table, i - information summary, p - processing element
                                                                        //dom plug-ins: B - button, R - ColReorder, S - Scroller, P - SearchPanes, Q - SearchBuilder
            responsive: true,                                           //dynamic insertion and removal of columns from the table.
            scrollX: true,                                              //enable horizontal scrollbar when using phone
            processing: true,                                           //show processing info
            language: {
                processing: 'Retrieving info...'
            },
            serverSide: true,                                           //to enable processing millions of data
            ajax:{
                url: "/calendar/calibration/getDatatable/"+type,              //get data from controller, init
                dataSrc: function ( json ) {
                    //alert(json.comments);
                    return json.data;
                }
            },
            columnDefs: [
                { "orderable": false, "targets":  [ 0, -1 ] }        //remove sorting for first and last column
            ],
            order: [],                                               //enable sorting for the rest of columns
            columns: [
                {data: 'DT_RowIndex', name: 'ID'},                   //datatable index row, incremental in nature
                { data: 'title', name: 'title' },
                { data: 'category.name', name: 'category.name'},
                { data: 'rrule', name: 'rrule'},
                { data: 'created_at',  
                render:function ( data, type, row, meta ) {
                    if (data == null){
                        return '';
                    }else{
                        return moment(data).format('YYYY-MM-DD HH:mm:ss');
                    }
                }, name: 'created_at'},
                { data: 'created_by', name: 'created_by'},
            ],
            pageLength: 25,                                           //set default length records
            lengthMenu: [                                            //enable user to display how many records per page
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                {
                    extend: 'colvis',
                }, 
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2,],
                    },
                },    
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    },
                }, 
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    },
                }, 
            ],
            //"deferLoading": 57
            "deferRender": true                                        //might improve performance for large datatable
        });
    }
</script>
@endsection