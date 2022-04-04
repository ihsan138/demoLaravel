@extends('adminlte::page')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Navigation
                </div>
                <div class="card-body">
                    <a href="" data-target="#modal-create" data-toggle="modal" class="btn btn-primary btn-sm">CREATE BACKUP</a>
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div id="backup-state">
                @if (file_exists(storage_path('app/backup-temp/temp/manifest.txt')))
                    <div class="callout callout-success">
                        A backup is running!
                    </div>
                @else
                    <div class="callout callout-info">
                        No backup is running.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Backups
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-bordered table-hover"" role="grid" width="100%" aria-describedby="example2_info">
                                <thead>
                                    <tr role="row">
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Size</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
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
            <h4 class="modal-title">Create Backup</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Confirm to create backup?</p>
            <form id="formCreate">
                @csrf 
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
$(document).ready(function(){
    $('#datatable')
        .on('processing.dt', function(e, settings, processing) { //when processing datatables, show pace instead of 'processing'
            if (processing) {
                Pace.restart();
                Pace.bar.render();
            } 
        })
        .DataTable({
        responsive: true,                                        //dynamic insertion and removal of columns from the table.
        scrollX: true,                                           //enable horizontal scrollbar when using phone
        //processing: true,                                      //show processing info
        //language: {
        //    processing: 'loading...'
        //},
        serverSide: true,                                        //to enable processing millions of data
        ajax:{
            url: "/backups/getDatatable",                        //get data from controller, init
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
            { data: 'name', name: 'name' },
            { data: 'size', name: 'size' },
            { data: 'action', name: 'action'}
        ],
        lengthMenu: [                                            //enable user to display how many records per page
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
    });

    $(document).on('click', '.delete', function(){
        var id =  $(this).data('id');
        $('#modal-delete').modal('show');
        deleteForm(id);
    });

    /****************************************************************** SUBMIT FORMS ******************************************************************/
    /* form submission */ 

    //create
    $("#formCreate" ).unbind();                                 //need to unbind first, if not recurring click will occur
    $('#formCreate').submit(function (e) {
        e.preventDefault()                                      // prevent the form from 'submitting'
        var currentForm = this;                         
        //var url = e.target.action                         // get the target
        var url = '/backups/create'                         // get the target, use this because generic
        var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
        $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
            url: url,
            type: 'GET',
            data: formData,
            beforeSend:function(){
                $('#submitCreate').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
                $('#modal-create').modal('hide');             //hide modal here instead of success
                document.getElementById("backup-state").innerHTML = '<div class="callout callout-success">A backup is running!</div>';  //show callout
            },
            success: function (response) {
                $('#submitCreate').attr('disabled', false);
                $(":input").removeClass("is-invalid");                                                              //remove all input is-invalid class
                document.getElementById("backup-state").innerHTML = '<div class="callout callout-info">No backup is running.</div>'; //show callout
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
                        //$('#modal-create').modal('hide');           //hide modal only if success
                        currentForm.reset();                        //reset form only if success
                        $('#datatable').DataTable().ajax.reload();  //reload table only if success
                    }
                    toastr.fire({                                   //always show toast
                        type: response.notification.alert_type,
                        title: response.notification.message,
                    });
                }
            },
            contentType: false,                             // false: to ensure that jQuery doesn’t insert its own Content-Type header
            processData: false,                             // false: Prevent jQuery from attempting to URL-encode the payload (to send a DOMDocument, or other non-processed data.)
        });
    });

    //delete
    function deleteForm(id){
        //submit form
        $("#formDelete" ).unbind();                             //need to unbind first, if not recurring click will occur
        $('#formDelete').submit(function (e) {
            e.preventDefault()                                  // prevent the form from 'submitting'
            var currentForm = this;                         
            //var url = e.target.action                         // get the target
            var url = '/backups/'+id                            // get the target, use this because generic
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
                    $('#datatable').DataTable().ajax.reload();      //reload table             
                    if(response.notification){                  
                        toastr.fire({                               //always show toast
                            type: response.notification.alert_type,
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