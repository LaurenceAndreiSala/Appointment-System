@extends('layouts.layout')

@section('title', 'Appointment App | MainPage')

@section('css')
@endsection
@include('includes.bgimage')
@section('content')
<div class="container-fluid min-vh-100 d-flex flex-column justify-content-between">
<nav class="navbar navbar-expand-lg   m-0 pf-2">
    <div class="container-fluid ontainer-sm">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('img/logo.png') }}" alt="SPC Logo" class="logo" style="height:100px;">
        </a>
</nav>


    <!-- Main Section -->
    <div class="text-center my-auto px-3">
        <h1 class="display-5 fw-bold text-white">"By Scheduling an Appointment you're taking"</h1>
        <p class="lead text-white ">A step towards making your dreams a Reality</p>
        <a class="btn bg-info btn-lg px-4 py-2 text-white" href="{{ route('login') }}">Request now!</a>
    </div>
    

 <!-- Footer -->
<footer class="bg-info bg-gradient text-light py-3 text-center mt-auto w-100">
    <div class="container">
        <p class="fw-bold mb-1">Contact Us:</p>
        <p class="mb-0">
            <i class="fas fa-phone me-2"></i>
            <a class="text-light text-decoration-none" href="tel:+63 221 6246">+063 975 477 2250</a>
            <br>
            <i class="fas fa-envelope me-2"></i>
            <a class="text-light text-decoration-none" href="mailto:spc.edu.ph@gmail.com">spc.edu.ph@gmail.com</a>
        </p>
    </div>
</footer>

</div>

@endsection
