@extends('adminlte::page')

@section('meta_tags')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div style="height: 600px;">
        <div id="fm"></div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
@endsection