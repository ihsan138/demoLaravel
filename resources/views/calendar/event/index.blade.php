@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    
    @include('calendar.navigation-bar')

    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Legends
                </div>
                <div class="card-body">
                    @foreach($calendarEventCategory as $category)
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
                    Calendar Event
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
</div>

<!-- MODALS -->
<div class="modal fade" id="modal-create" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create Event</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formCreate">
                @csrf 
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">Event Title</label>
                    <div class="col-md-10">
                        <input id="create-title" name="title" type="text" class="form-control" required="required">
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <!-- Select -->
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label text-md-right">Category</label>
                    <div class="col-md-10">
                        <select class="form-control" id="calendar_event_category_id" name="calendar_event_category_id" required="required">
                        @foreach($calendarEventCategory as $category)
                            <option value='{{ $category->id }}'>{{ $category->name }}</option>	
                        @endforeach				
                        </select>
                    </div>
                </div>
                <!-- Date range -->
                <div class="form-group row">
                    <label for="file" class="col-md-2 col-form-label text-md-right">Date</label>
                    <div class="col-md-10">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input id="create-daterange" name="daterange" type="text" class="form-control float-right" required="required">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <!-- /.form group -->
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
                <h4 class="modal-title">Edit Event</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    @csrf 
                    @method('PUT')

                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">Event Title</label>
                        <div class="col-md-10">
                            <input id="edit-title" name="title" type="text" class="form-control" required="required">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <!-- Select -->
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label text-md-right">Category</label>
                        <div class="col-md-10">
                            <select class="form-control" id="edit-calendar_event_category_id" name="calendar_event_category_id" required="required">
                            @foreach($calendarEventCategory as $category)
                                <option value='{{ $category->id }}'>{{ $category->name }}</option>	
                            @endforeach				
                            </select>
                        </div>
                    </div>
                    <!-- Date range -->
                    <div class="form-group row">
                        <label for="file" class="col-md-2 col-form-label text-md-right">Date</label>
                        <div class="col-md-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input id="edit-daterange" name="daterange" type="text" class="form-control float-right" required="required">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /.form group -->
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
                <h4 class="modal-title">Event Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <dl class="dl-horizontal">
                    <dt>Event Title</dt>
                    <dd id="show-title"></dd>
                    <dt>Event Category</dt>
                    <dd id="show-category"></dd>
                    <dt>Start Time</dt>
                    <dd id="show-start"></dd>
                    <dt>End Time</dt>
                    <dd id="show-end"></dd>
                    <dt>Created By</dt>
                    <dd id="show-created-by"></dd>
                    <dt>Updated By</dt>
                    <dd id="show-updated-by"></dd>
                    </dl>
                </div>
            </div>
            <div class="modal-footer">
                <button id="edit-event" type="submit" class="btn btn-warning btn-group">Edit</button>
                <button id="delete-event" type="submit" class="btn btn-danger btn-group">Delete</button>
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
        $('#create-daterange').daterangepicker({
            timePicker: true,
            timePickerIncrement: 15,
            locale: {
                format: 'DD/MM/YYYY hh:mm A'
            }
        });
        //define daterangepicker
        $('#edit-daterange').daterangepicker({
            timePicker: true,
            timePickerIncrement: 15,
            locale: {
                format: 'DD/MM/YYYY hh:mm A'
            }
        });

        /****************************************************************************************************************************************/
        /*           CALENDAR                                                                                                                   */
        /****************************************************************************************************************************************/

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            editable: true,
            droppable: true,
            displayEventTime:true,
            displayEventEnd :true,
            
            // https://fullcalendar.io/docs/date-formatting
            titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                day: '2-digit',
                year: 'numeric',
                month: 'long',
            },
            headerToolbar: {
                center: 'dayGridMonth,timeGridWeek,listWeek' // buttons for switching between views
                // center: 'dayGridMonth,timeGridWeek,timeGridFourDay' // buttons for switching between views
            },
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
            events: "/calendar/event/eventData", // SITEURL + "fullcalendar",
            // events: [
            //     {
            //     title  : 'event1',
            //     start  : '2021-09-17',
            //     borderColor: 'red',
            //     backgroundColor: '#C34232',
            //     },
            //     {
            //     title  : 'event2',
            //     start  : '2021-09-28',
            //     end    : '2021-09-29'
            //     },
            //     {
            //     title  : 'event3',
            //     start  : '2021-09-31T12:30:00',
            //     allDay : false // will make the time show
            //     }
            // ],
            // eventRender: function (event, element, view) {
            //     if (event.allDay === 'true') {
            //         event.allDay = true;
            //     } else {
            //         event.allDay = false;
            //     }
            // },
            
            selectable: true,
            // selectHelper: true,
            select: function (data) {
                $('#modal-create').modal('show');
                $('#create-daterange').data('daterangepicker').setStartDate(data.start); //'03/01/2014'
                $('#create-daterange').data('daterangepicker').setEndDate(data.end);
            },
            // eventClick:function(data) /* Fires when clicking an event */
            
            eventClick: function(data)
            {
                var id = data.event.id;
                var title = data.event.title;
                var calendar_event_category_id = data.event.extendedProps.calendar_event_category_id;
                var category = data.event.category;
                // var start = FullCalendar.formatDate(data.event.start, "DD/MM/YYYY HH:mm:ss");    //previous version fullcalendar
                // var end = $.fullCalendar.formatDate(data.event.end, "DD/MM/YYYY HH:mm:ss");      //previous version fullcalendar
                var start =$.fullCalendar.moment(data.event.start).format('DD/MM/YYYY HH:mm:ss');
                var end =$.fullCalendar.moment(data.event.end).format('DD/MM/YYYY HH:mm:ss');
                var created_by = data.event.extendedProps.created_by;
                var updated_by = data.event.extendedProps.updated_by;

                //Show event
                $('#show-title').html(title);
                $('#show-start').html(start);
                $('#show-category').html(category);
                $('#show-end').html(end);
                $('#show-created-by').html(created_by);
                $('#show-updated-by').html(updated_by);
                $('#modal-show').modal('show');
                
                //Edit event
                $("#edit-event" ).unbind();                         //need to unbind first, if not recurring click will occur
                $('#edit-event').on('click', function(){
                    $('#modal-show').modal('hide');
                    $('#modal-edit').modal('show');
                    $('#edit-title').val(title);
                    $('#edit-calendar_event_category_id').val(calendar_event_category_id);
                    $('#edit-daterange').data('daterangepicker').setStartDate(start); //'03/01/2014'
                    $('#edit-daterange').data('daterangepicker').setEndDate(end);
                    editForm(id);
                });

                //Delete event
                $("#delete-event" ).unbind();                         //need to unbind first, if not recurring click will occur
                $('#delete-event').on('click', function(){
                    $('#modal-show').modal('hide');
                    $('#modal-delete').modal('show');
                    $('#delete-title').html(title);
                    deleteForm(id);
                });
            },
            eventDrop:function(data)
            {
                var id = data.event.id;
                var title = data.event.title;
                // var start_date = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");     //previous version fullcalendar
                // var end_date = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");         //previous version fullcalendar
                var start_date =$.fullCalendar.moment(data.event.start).format('DD/MM/YYYY HH:mm:ss');
                var end_date =$.fullCalendar.moment(data.event.end).format('DD/MM/YYYY HH:mm:ss');

                //create FormData
                var formData = new FormData();
                formData.append('id', id);
                formData.append('title', title);
                formData.append('start_date', start_date);
                formData.append('end_date', end_date);

                //call to ajax
                $.ajax({                                         
                    url: '/calendar/event/updateDroppable/'+id,
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{csrf_token()}}' },     //important to add here
                    data: formData,
                    success: function (response) {
                        if(response.notification){
                            if(response.notification.alert_type == 'success'){
                                // calendar.fullCalendar('refetchEvents'); //refetch only if success, FullCalendar 3.10.2
                                calendar.refetchEvents();                  //required for FullCalendar 5.5.0
                            }
                            toastr.fire({                               //always show toast
                                icon: response.notification.alert_type,
                                title: response.notification.message,
                            });
                        }
                    },
                    contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
                    processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
                });

            },
            eventResize: function(info) {
            },
        });
        calendar.render(); //required for FullCalendar 5.5.0

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
                var url = '/calendar/event'                              // get the target, use this because generic
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
                var url = '/calendar/event/'+id                          // get the target, use this because generic
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
                var url = '/calendar/event/'+id                    // get the target, use this because generic
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
    });
    </script>
@endsection