@extends('layouts.main')

@section('title', 'Appointment App | MainPage')
@include('includes.navbar')
@section('content')
<div class="container-fluid section-main p-5">
    <div class="main">
        <div class=" text-center">
          <h1 class="heading-primary display-2 p-5 m-5 text-white">"By Scheduling an Appointment you're taking"</h1>
         </div>
         <p class="main-text text-center my-5 pt-5 text-white">
          A steps towards making your dreams a Reality
         </p>

        <div class="main-btn text-center">
        <a class="btn btn-cta btn-lg" href="requestforms"  role="button">Request now!</a>
        </div>
      </div>
      <footer class="footer">
        <div class="info-col">
          <p class="footer-heading">Contact Us:</p>
            <address class="contacts">
               <p>
                <a href="#">
                  <img class="icons" alt="" src="img/icon2.png" />
                 </a>
                <a class="footer-link p-4" href="tel:+63 1234 567 89">+063  221 6246</a
                  ><br />

                  <a href="#">
                    <img class="icons" alt="" src="img/icon1.png" />
                   </a>
                  <a class="footer-link" href="mailto:spc.edu.ph@gmail.com"
                  >spc.edu.ph@gmail.com</a>
              </p>
            </address>
          </ul>
        </div>
      </footer>
</div>
@endsection