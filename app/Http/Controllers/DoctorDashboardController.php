<?php
// DoctorDashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        // Count doctors (role_id = 2 as example, adjust if needed)
        $doctorCount = User::where('role_id', 2)->count();

        $patientCount = User::where('role_id', 3)->count();
        $patients = User::where('role_id', 3)->get();

        // Optional: count patients (role_id = 3 as example)
       $totaluserCount = User::whereIn('role_id', [2, 3])->count();

        // Get all users with roles (for the Manage Users modal)
        $users = User::with('role')->get();

        // Pass variables to the view
        return view('doctor.doctor-dashboard', compact(
            'users', 'doctorCount', 'patientCount', 'totaluserCount', 'patients'
        ));
    }
}