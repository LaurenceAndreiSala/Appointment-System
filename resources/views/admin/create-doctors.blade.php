@extends('layouts.layout')

@section('content')
@include('includes.leftnavbar')

<!-- Main Content -->
  <div class="main">
    <h1>Create Doctor Account</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('doctors.store') }}">
        @csrf

        <label>First Name:</label>
        <input type="text" name="firstname" required><br><br>

        <label>Last Name:</label>
        <input type="text" name="lastname" required><br><br>

        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label>
        <input type="password" name="password_confirmation" required><br><br>

        <button type="submit">Create Doctor</button>
    </form>
    
@endsection