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
                    <a href="{{route('permissions.create')}}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Create</a>
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
                    Permissions
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
                                        <th>Name</th>
                                        <th>Guard Name</th>
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
            url: "/permissions/getDatatable/"+type,              //get data from controller, init
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
            { data: 'guard_name', name: 'guard_name' },
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
@endsection