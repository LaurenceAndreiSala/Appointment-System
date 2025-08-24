@extends('layouts.default')
@extends('layouts.general')

@section('title', 'Appointment App | Status')


<div class="container-fluid">
    <div class="row">
        <div class=".col">
            <div class="card-sidebar d-flex flex-column" style="height: 100vh;">
              <div class="pt-3 mb-3">
                <div class="mb-5 pt-5 pl-3">
                  <img src="{{ asset('img/logo7.png')}}" alt="Profile Picture">
                </div>
              </div>
              <div class="mb-5 pl-3 text-white">
                <p class="cta-text">Hi, {{ Auth::user()->firstname }}!</p>
              </div>
              <div class="nav flex-grow-1">
                <ul class="list-unstyled">
                    <li class="mb-5 pl-3"><a class="text-white text-decoration-none" href="{{ route('user.notification')}}">Inbox</a></li>
                  <li class="mb-5 pl-3"><a class="text-white text-decoration-none" href="{{ asset('mainpage')}}">Home</a></li>
                  <li class="mb-5 pl-3"><a class="text-white text-decoration-none" href="status">Appointment</a></li>
                  <li class="mb-5 pl-3"><a class="text-white text-decoration-none" href="{{ route('user.settings')}}">Settings</a></li>
                </ul>
              </div>
              <div class="mt-auto mb-5 pl-3">
                <ul class="list-unstyled">
                  <li class="pl-3"><a class="text-white text-decoration-none" href="{{ route('logout')}}" style="font-size: 1.8rem;">Logout</a></li>
                </ul>
              </div>x
            </div>
          </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-4 pt-5 text-center ">
                        <h1 class="display-3">Appointment</h1>
                    </div>
                    <table class="table table-striped table-hover mt-5">
                        <thead  class="thead-dark">
                            <tr>
                                <th scope="col">Appointment</th>
                                <th scope="col" class="text-right">Status</th>
                                <th scope="col" class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment )
                            <tr>
                                <td>
                                    {{ $appointment->typesofrequirements }}
                                </td>
                                <td class="text-right ml-5">
                                    {{ $appointment->status }}
                                </td>
                                <td width="150" class="text-right">
                                    <a href="{{ route('view', ['id' => $appointment->id]) }}" class="btn btn-sm btn-circle btn-outline-info" title="Show"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
