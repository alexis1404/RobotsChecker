@extends('main_layout')

@section('header')
    <div class="header">
        <div class="container">
            <h1 align="center" class="main-text"> Robots checker</h1>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">

        @yield('checkForm')

    </div>
    @endsection
