<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretaryDashboardController extends Controller
{
    public function index() { return view('secretary.set-appointment'); }

    public function setSlot() { return view('secretary.slots'); }
    public function storeSlot(Request $request) { /* save slot */ }

    public function viewAppointments() { return view('secretary.appointments'); }
    public function bookAppointment(Request $request) { /* store appointment */ }
}
