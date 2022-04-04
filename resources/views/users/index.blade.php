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
                    @can('Manage users')
                        <a href="{{route('users.create')}}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Create</a>
                    @endcan
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
            @if (\Session::has('danger'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                    {!! \Session::get('danger') !!}
                </div>
            @endif
            @if (\Session::has('info'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {!! \Session::get('info') !!}
                </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Users
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="col-md-12">
                            <button onClick="myFunction('all')" class="btn btn-primary btn-sm">Show All</button>
                            <button onClick="myFunction('active')" class="btn btn-primary btn-sm">Show Active</button>
                            <button onClick="myFunction('trashed')" class="btn btn-danger btn-sm">Show Trashed</button>
                        </div>
                    </div>
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
                                        <th>Status</th>
                                        <th>Name</th>
                                        <th>Email</th>
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
<div class="modal fade" id="modal-approve" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm approve?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">Do you wish to approve&nbsp;<div id="approve-username"></div>&nbsp;?</div>
                <form id="formApprove" method="POST">
                    @csrf 
                    @method('PUT')
            </div>
            <div class="modal-footer justify-content-between">
                    <button id="submitApprove" type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- MODALS -->
<div class="modal fade" id="modal-reject" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm reject?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">Do you wish to reject&nbsp;<div id="reject-username"></div>&nbsp;?</div>
                <form id="formReject" method="POST">
                    @csrf 
                    @method('PUT')
            </div>
            <div class="modal-footer justify-content-between">
                    <button id="submitReject" type="submit" class="btn btn-danger">Reject</button>
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
    $(function() {
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
            url: "/users/getDatatable/"+type,              //get data from controller, init
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
            { data: 'status', name: 'status' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'action', name: 'action'}
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
<script>

/****************************************************************************************************************************************/
/* Approve/Reject users                                                                                                                 */
/****************************************************************************************************************************************/
//approve
$(document).on('click', '.approve', function(){
    var id =  $(this).data('id');
    var username =  $(this).data('username');
    $('#approve-username').html(username);
    $('#modal-approve').modal('show');
    approveForm(id);
});

function approveForm(id){
    //submit form
    $("#formApprove" ).unbind();                             //need to unbind first, if not recurring click will occur
    $('#formApprove').submit(function (e) {
        e.preventDefault()                                  // prevent the form from 'submitting'
        var currentForm = this;                         
        //var url = e.target.action                         // get the target
        var url = '/users/approve/'+id                            // get the target, use this because generic
        var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
        $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
            url: url,
            type: 'POST',
            data: formData,
            beforeSend:function(){
                $('#submitApprove').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
            },
            success: function (response) {
                $('#submitApprove').attr('disabled', false);     //remove all input is-invalid class                          
                $('#modal-approve').modal('hide');               //hide modal only if success        
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

//reject
$(document).on('click', '.reject', function(){
    var id =  $(this).data('id');
    var username =  $(this).data('username');
    $('#reject-username').html(username);
    $('#modal-reject').modal('show');
    rejectForm(id);
});

function rejectForm(id){
    //submit form
    $("#formReject" ).unbind();                             //need to unbind first, if not recurring click will occur
    $('#formReject').submit(function (e) {
        e.preventDefault()                                  // prevent the form from 'submitting'
        var currentForm = this;                         
        //var url = e.target.action                         // get the target
        var url = '/users/reject/'+id                            // get the target, use this because generic
        var formData = new FormData(this)                   // get form data with file functionality, $(this).serialize() for non-file functionality
        $.ajax({                                            // $.post(url, formData, function (response){ }) is a shorthand
            url: url,
            type: 'POST',
            data: formData,
            beforeSend:function(){
                $('#submitReject').attr('disabled','disabled');//avoid user from submitting buttons simultaneously
            },
            success: function (response) {
                $('#submitReject').attr('disabled', false);     //remove all input is-invalid class                          
                $('#modal-reject').modal('hide');               //hide modal only if success        
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
</script>
@endsection