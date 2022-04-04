@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row"><!-- use class:justify-content-center to center card content -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Navigation
                </div>
                <div class="card-body">
                    <a href="{{ URL::previous() }}" class="btn btn-primary btn-sm"><i class="fas fa-undo"></i> Back</a>
                    
                    <!-- only show if not deleted -->
                    @if(is_null($result->deleted_at)) 
                        <a href="" data-target="#modal-delete" data-toggle="modal" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> DELETE</a>
                    @else
                        <a href="" data-target="#modal-restore" data-toggle="modal" class="btn btn-primary btn-sm"><i class="fas fa-trash-restore"></i> RESTORE</a>
                    @endif
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="row"> <!-- use class:justify-content-center to center card content -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Calendar Calibration Category Information</div>
    
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> Name</strong>
                    <p class="text-muted">
                        {{$result->name}}
                    </p>
                    <strong><i class="fas fa-book mr-1"></i> Border Color</strong>
                    <p class="text-muted">
                        {{$result->borderColor}}
                    </p>
                    <strong><i class="fas fa-book mr-1"></i> Background Color</strong>
                    <p class="text-muted">
                        {{$result->backgroundColor}}
                    </p>
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
<div class="modal fade" id="modal-delete" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Delete</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Confirm to delete data?</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <form action="{{ route('calendar.calibration.category.destroy',$result->id)}}" method="POST">
                @csrf 
                @method('DELETE') <!-- override the method for delete -->
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>

        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-restore" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Restore</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Confirm to restore data?</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <form action="{{ route('calendar.calibration.category.restore',$result->id)}}" method="GET">
                @csrf 
                <button type="submit" class="btn btn-success">Restore</button>
            </form>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection