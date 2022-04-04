@extends('adminlte::page')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Default box -->
            <div class="card card-solid">
                <div class="card-body pb-0">
                    <div class="row d-flex align-items-stretch">
                        @foreach($result as $user)
                            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                                <div class="card bg-light">
                                    <div class="card-header text-muted border-bottom-0">
                                    {{$user->name}}
                                    </div>
                                    <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-7">
                                        <h2 class="lead"><b>{{$user->email}}</b></h2>
                                        <p class="text-muted text-sm"><b>Last seen: </b> {{$user->last_seen_at}}</p>
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone #: {{$user->telephone}}</li>
                                        </ul>
                                        </div>
                                        <div class="col-5 text-center">
                                            <img class="img-circle img-fluid" src="{{ asset('storage').'/'.$user->avatar }}" alt="User profile picture">
                                        </div>
                                    </div>
                                    </div>
                                    <div class="card-footer">
                                    <div class="text-right">
                                        <a href="{{route('profiles.show', $user->id)}}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> View Profile
                                        </a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                <nav aria-label="Contacts Page Navigation">
                    <ul class="pagination justify-content-center m-0">
                        {{ $result->links() }}
                    </ul>
                </nav>
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection