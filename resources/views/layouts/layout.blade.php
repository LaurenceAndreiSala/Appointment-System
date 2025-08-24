<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="School Registrar Appointment System" >


    <link rel="icon" href="{{ asset('img/logo1.png')}}" />
    <link rel="manifest" href="{{ asset('manifest.webmanifest')}}" />

    <title>@yield('title', 'Appointment App')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link  href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">

    <link  href=" @yield('css')" rel="stylesheet">
    <link  href="{{ asset('css/admin.css')}}" rel="stylesheet">
    <link  href="{{ asset('css/admindashboard.css')}}" rel="stylesheet">
    <link  href="{{ asset('css/patient.css')}}" rel="stylesheet">
    <link  href="{{ asset('css/doctor.css')}}" rel="stylesheet">
    <link  href="{{ asset('css/secretary.css')}}" rel="stylesheet">
</head>
<body>
      @yield('content')

      <src hreg
</body>
</html>
