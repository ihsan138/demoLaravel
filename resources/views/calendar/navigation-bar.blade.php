<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Navigation
            </div>
            <div class="card-body">
                <div class="btn-group " role="group" aria-label="Button group with nested dropdown">

                    <div class="btn-group blocks" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-calendar"></i> <span>Calendar Event</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('calendar.event.index')}}">View Calendar</a>
                            <a class="dropdown-item" href="{{route('calendar.event.category.index')}}">View Category</a>
                            <a class="dropdown-item" href="{{route('calendar.event.category.create')}}">Create Category</a>
                        </div>
                    </div>
                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-calendar"></i> <span>Calendar Calibration</span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{route('calendar.calibration.index')}}">View Calendar</a>
                            <a class="dropdown-item" href="{{route('calendar.calibration.category.index')}}">View Category</a>
                            <a class="dropdown-item" href="{{route('calendar.calibration.category.create')}}">Create Category</a>
                        </div>
                    </div>
                </div>
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups"></div>
            </div>
            <!--
            <div class="card-footer">
            </div>
            -->
        </div>
    </div>
</div>