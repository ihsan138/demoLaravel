@extends('adminlte::page')

@section('content')
    <div class="title m-b-md">
        You cannot access this page! This is for only {{ $role }} only.
    </div>
@endsection