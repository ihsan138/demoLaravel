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
                    @if(Auth::id() == $result->id)
                        <a href="{{ route('profiles.edit',$result->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-user-edit"></i> Edit Profile</a>
                        <a href="{{ route('change.password',$result->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-undo"></i> Change password</a>
                    @endif
                    @can('Manage users')
                        <a href="{{ route('users.edit',$result->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-user-edit"></i> Edit User</a>
                        <a href="{{ route('change.password.admin',$result->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-undo"></i> Change password Admin</a>
                    @endcan
                </div>
                <!--
                <div class="card-footer">
                </div>
                -->
            </div>
        </div>
    </div>
    <div class="row"> <!-- use class:justify-content-center to center card content -->
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                      <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage').'/'.$result->avatar }}" alt="User profile picture">
                </div>
      
                <h3 class="profile-username text-center">{{$result->name}}</h3>
      
                <p class="text-muted text-center">
                  Last seen at : {{$result->last_seen_at}}  
                </p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card">
                <div class="card-header">User information</div>
    
                <div class="card-body">
                    <strong><i class="fas fa-book mr-1"></i> Details</strong>
                    <p class="text-muted">
                        Name: {{$result->name}}</br>
                        Employe No: {{$result->user_number}}</br>
                        Designation: {{$result->designation}}</br>
                    </p>
                    <strong><i class="fas fa-users mr-1"></i> Supervisors</strong>
                    <ol>
                        <li>{{$result->supervisor1->name ?? ''}}</li>
                        <li>{{$result->supervisor2->name ?? ''}}</li>
                    </ol>
                    <strong><i class="fas fa-at mr-1"></i> Email</strong>
                    <p class="text-muted">
                        {{$result->email}}
                    </p>
                    <strong><i class="fas fa-phone mr-1"></i> Telephone</strong>
                    <p class="text-muted">
                        {{$result->telephone}}
                    </p>
                    <strong><i class="fas fa-user-tag mr-1"></i> Roles</strong>
                    <ol>
                    @foreach ($selected_roles as $role)
                        <li>{{$role}}</li>
                    @endforeach
                    </ol>
                    <strong><i class="fas fa-toggle-on mr-1"></i> Status</strong>
                    <p class="text-muted">
                        Current status: {{$result->status}}</br>
                        Last seen at : {{$result->last_seen_at}}</br>
                        Created at : {{$result->created_at}}</br>
                        Updated at : {{$result->updated_at}}</br>
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
@endsection