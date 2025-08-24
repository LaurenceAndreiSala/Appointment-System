<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;


class IntroPageController extends Controller
{
    //

    public function intropage(){

        return view('welcome');
    }
}